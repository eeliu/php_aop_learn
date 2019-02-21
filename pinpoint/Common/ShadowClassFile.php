<?php
/**
 * User: eeliu
 * Date: 2/13/19
 * Time: 4:41 PM
 */

namespace pinpoint\Common;

use pinpoint\Common\ClassFile;
use PhpParser\BuilderFactory;
use PhpParser\PrettyPrinter;
use PhpParser\Node;
use pinpoint\Common\PluginParser;

class ShadowClassFile extends ClassFile
{
    private $factory;


    private $classNode;
    private $useArray = [];
    private $methodNodes = [];

    public function __construct($prefix = 'Proxied_')
    {
        parent::__construct($prefix);
        $this->factory= new BuilderFactory();
    }


    public function handleEnterNamespaceNode(&$node)
    {
        parent::handleEnterNamespaceNode($node);

    }

    public function handleEnterClassNode(&$node)
    {
        parent::handleEnterClassNode($node);

        assert($node instanceof Node\Stmt\Class_);
        $extClass = $this->prefix.$node->name->toString();
        $this->classNode  = $this->factory->class(trim($node->name->toString()))->extend($extClass);
        $this->useArray[] = $this->namespace.'\\'.$extClass;

//        const MODIFIER_STATIC    =  8;
//        const MODIFIER_ABSTRACT  = 16;
//        const MODIFIER_FINAL     = 32;

        switch($node->flags) {
            case Node\Stmt\Class_::MODIFIER_FINAL:
                $this->classNode->makeFinal();
                break;
            case Node\Stmt\Class_::MODIFIER_ABSTRACT:
                $this->classNode->makeAbstract();
                break;
           default:
                break;
        }
    }

    public static function convertParamsName2Arg($params)
    {
        assert(is_array($params));

        $args = [];

        foreach ($params as $param)
        {
            assert($param instanceof Node\Param);
            $args [] = new Node\Arg($param->var);
        }
        return  $args;
    }

    public function handleClassLeaveMethodNode(&$node,&$info)
    {
        /// todo this func looks ugly

        assert($node instanceof Node\Stmt\ClassMethod);

        list($mode, $namespace, $className) = $info;

        $np = $namespace . '\\' . $className;

        if(!in_array($np,$this->useArray)){
            $this->useArray[] = $np;
        }

        // use plugins\CommonPlugin
        $thisFuncName = $node->name->toString();


        $funcVar = new Node\Arg(new Node\Scalar\MagicConst\Method());
        $selfVar = new Node\Arg(new Node\Expr\Variable('this'));

        $pluginArgs  = array_merge([$funcVar,$selfVar],ShadowClassFile::convertParamsName2Arg($node->params));

        $thisMethod = $this->factory->method($thisFuncName);

        if ($node->flags & Node\Stmt\Class_::MODIFIER_PUBLIC) {
            $thisMethod->makePublic();
        }

        if ($node->flags & Node\Stmt\Class_::MODIFIER_PRIVATE) {
            $thisMethod->makePrivate();
        }

        if ($node->flags & Node\Stmt\Class_::MODIFIER_ABSTRACT) {
            $thisMethod->makeAbstract();
        }

        if ($node->flags & Node\Stmt\Class_::MODIFIER_PROTECTED) {
            $thisMethod->makeProtected();
        }

        if ($node->flags & Node\Stmt\Class_::MODIFIER_STATIC) {
            $thisMethod->makeStatic();
        }

        $thisMethod->addParams($node->params);
        if($node->returnType){
            $thisMethod->setReturnType($node->returnType);
        }

        /// $var = new CommonPlugins(__FUNCTION__,self,$p);
        $newPluginsStm = new Node\Stmt\Expression(new Node\Expr\Assign(new Node\Expr\Variable("var"),
            $this->factory->new($className, $pluginArgs)));

        $thisMethod->addStmt($newPluginsStm);

        $tryBlock = [];
        $catchNode = [];

        if ($mode & PluginParser::BEFORE)
        {
            // $var->onBefore();
            $tryBlock[] = new Node\Stmt\Expression(
                $this->factory->methodCall(new Node\Expr\Variable("var"), "onBefore"));
        }

        if ($this->hasRet) {
            /// $ret = paraent::$thisFuncName();
            $tryBlock[] = new Node\Stmt\Expression(new Node\Expr\Assign(
                new Node\Expr\Variable("ret"),
                new Node\Expr\StaticCall(new Node\Name("parent"),
                    new Node\Identifier($thisFuncName),
                    ShadowClassFile::convertParamsName2Arg($node->params))));

            /// $var->onEnd($ret);
            if($mode & PluginParser::END)
            {
                $tryBlock[] = new Node\Stmt\Expression(
                    $this->factory->methodCall(
                        new Node\Expr\Variable("var"),
                        "onEnd",
                        [new Node\Expr\Variable('ret')]
                    )
                );
            }

            /// return $var;
            $tryBlock[] = new Node\Stmt\Return_(new Node\Expr\Variable('ret'));

        } else {
            /// paraent::$thisFuncName();

            $tryBlock[] = new Node\Stmt\Expression($this->factory->staticCall(
                new Node\Name("parent")
                , new Node\Identifier($thisFuncName),
                ShadowClassFile::convertParamsName2Arg($node->params)));

            /// $var->onEnd($ret);
            if($mode & PluginParser::END)
            {
                $tryBlock[] = new Node\Stmt\Expression(
                    $this->factory->methodCall(
                        new Node\Expr\Variable("var"),
                        "onEnd",
                        [new Node\Arg(new Node\Expr\ConstFetch(new Node\Name('null')))])
                );
            }
        }


        $expArgs = [];
        $expArgs[] = new Node\Arg(new Node\Expr\Variable('e')) ;

        if ($mode & PluginParser::EXCEPTION) {

            $catchBlock[] = new Node\Stmt\Expression(
                $this->factory->methodCall(new Node\Expr\Variable("var"),
                    "onException",$expArgs));

        }

        $catchBlock[] = new Node\Stmt\Throw_(new Node\Expr\Variable("e"));

        $catchNode[] = new Node\Stmt\Catch_([new Node\Name('\Exception')],
                                    new Node\Expr\Variable('e'),
                                    $catchBlock);


        $tryCatchFinallyNode = new Node\Stmt\TryCatch($tryBlock,$catchNode);

        $thisMethod->addStmt($tryCatchFinallyNode);
        $this->methodNodes[] = $thisMethod;
    }

    public function handleAfterTravers(&$nodes,&$mFuncAr)
    {
        $useNodes = [];
        foreach ($this->useArray as $useAlias){
            $useNodes[] = $this->factory->use($useAlias);
        }

        $this->fileNode = $this->factory->namespace($this->namespace)
            ->addStmts($useNodes)
            ->addStmt(
                $this->classNode->addStmts($this->methodNodes)
            );

        return $this->fileNode->getNode();
    }

    function handleLeaveNamespace(&$nodes, &$mFuncAr)
    {
        // do nothing
    }
}