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
    private $useNodes = [];
    private $methodNodes = [];

    public function __construct($prefix = 'Proxied_')
    {
        parent::__construct($prefix);
        $this->factory= new BuilderFactory();
    }


    public function handleEnterNamespaceNode(&$node)
    {
        parent::handleEnterNamespaceNode($node);
        assert($node instanceof Node\Stmt\Namespace_);
    }

    public function handleEnterClassNode(&$node)
    {
        parent::handleEnterClassNode($node);
        assert($node instanceof Node\Stmt\Class_);

        $this->classNode = $this->factory->class(trim($node->name->toString()))->extend($this->prefix.'_'.$node->name->toString());

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


    public function handleClassLeaveMethodNode(&$node,&$info)
    {
        parent::handleClassEnterMethodNode($node);
        assert($node instanceof Node\Stmt\ClassMethod);
        list($mode, $namespace, $className) = $info;
        // use plugins\CommonPlugin
        $this->useNodes[] = $this->factory->use($namespace . '\\' . $className);

        $pluginArgs = $node->params;
        $thisFuncName = $node->name->toString();


        $funcVar = new Node\Arg(new Node\Scalar\MagicConst\Method());
        $selfVar = new Node\Arg(new Node\Expr\ConstFetch(new Node\Name('this')));

        array_unshift($pluginArgs, $funcVar, $selfVar);

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

        $newPluginsStm = new Node\Stmt\Expression(new Node\Expr\Assign(new Node\Expr\Variable("var"),
            $this->factory->new($className, $pluginArgs)));

        $thisMethod->addStmt($newPluginsStm);

        $tryBlock = [];
        $catchNode = [];
        $finallyBlock = [];


        if ($mode & PluginParser::BEFORE)
        {
            // $var->onBefore();
            $tryBlock[] = $this->factory->methodCall(new Node\Expr\Variable("var"), "onBefore", []);
        }

            // paraent::$thisFuncName
        if ($this->hasRet) {
            $tryBlock[] = new Node\Stmt\Expression(new Node\Expr\Assign(
                new Node\Expr\Variable("ret"),
                new Node\Expr\StaticCall(new Node\Name("parent"),
                    new Node\Identifier($thisFuncName), $node->params)));
        } else {
            $tryBlock[] = $this->factory->staticCall(
                new Node\Expr\Variable("parent")
                , new Node\Identifier($thisFuncName), $node->params);
        }


        $expArgs = [];
        $expArgs[] = new Node\Arg(new Node\Expr\Variable('e')) ;
        if ($mode & PluginParser::EXCEPTION) {
            $catchBlock[] = $this->factory->methodCall(new Node\Expr\Variable("var"), "onException",$expArgs);
        } else {
            $catchBlock[] = new Node\Stmt\Throw_(new Node\Expr\Variable("e"));
        }

        $catchNode[] = new Node\Stmt\Catch_([new Node\Name('\Exception')],
                                    new Node\Expr\Variable('e'),
                                    $catchBlock);


        /// if onEnd
        if ($this->hasRet) {
            $finallyBlock[] = new Node\Stmt\Expression(
                $this->factory->methodCall(
                    new Node\Expr\Variable("var"),
                    "onEnd",
                    [new Node\Expr\Variable('ret')])
            );
        }else{
            $finallyBlock[] = new Node\Stmt\Expression(
                $this->factory->methodCall(
                    new Node\Expr\Variable("var"),
                    "onEnd",
                    [new Node\Expr\Variable('null')])
            );
        }

        $tryCatchFinallyNode= new Node\Stmt\TryCatch($tryBlock,$catchNode,$finallyBlock);

        $thisMethod->addStmt($tryCatchFinallyNode);
        $this->methodNodes[] = $thisMethod;
    }

    public function handleAfterTravers(&$nodes,&$mFuncAr)
    {
        $this->fileNode = $this->factory->namespace($this->namespace)
            ->addStmts($this->useNodes)
            ->addStmt($this->classNode)
            ->addStmts($this->methodNodes);
        return $this->fileNode;
    }

    function handleLeaveNamespace(&$nodes, &$mFuncAr)
    {
        // do nothing
    }
}