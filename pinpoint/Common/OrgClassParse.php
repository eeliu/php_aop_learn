<?php
namespace pinpoint\Common;

use pinpoint\Common\OriginClassFile;
use pinpoint\Common\ShadowClassFile;
use pinpoint\Common\UserCodeVisitor;

use PhpParser\Lexer;
use PhpParser\Parser;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor;
use PhpParser\NodeVisitorAbstract;
use PhpParser\PrettyPrinter;
use PhpParser\Error;
use PhpParser\Node;
use PhpParser\BuilderFactory;


class OrgClassParse
{
    private $originFile;
    private $dir;
    private $lexer;
    private $parser;
    private $nameResolver;
    private $traverser;
    private $printer;

    public $cfg;

    private $originClassNode;

    private $rawOrigStmts;

    public $classIndex = [];

    public $className;// app\foo\DBManager

    const PRE_FIX = 'Proxied_';

    public $mFuncAr;

    public $originClass;
    public $shadowClass;

    public $orgClassPath;
    public $shadowClassPath;


    public function __construct($file,$cl,$info,&$cfg)
    {
        assert(file_exists($file));
        $this->cfg = &$cfg;
        $this->className = $cl;
        $this->mFuncAr = $info;
        $this->originFile = $file;
        $this->dir = dirname($file);

        $this->lexer = new Lexer\Emulative([
            'usedAttributes' => [
                'comments',
                'startLine', 'endLine',
                'startTokenPos', 'endTokenPos',
            ],
        ]);

        $this->parser = new Parser\Php7($this->lexer, [
            'useIdentifierNodes' => true,
            'useConsistentVariableNodes' => true,
            'useExpressionStatements' => true,
            'useNopStatements' => false,
        ]);

        $this->nameResolver = new NodeVisitor\NameResolver(null, [
            'replaceNodes' => false
        ]);

        $this->traverser = new NodeTraverser();
        $this->traverser->addVisitor(new NodeVisitor\CloningVisitor());
        $this->traverser->addVisitor(new UserCodeVisitor($this));

        $this->printer = new PrettyPrinter\Standard();
        $this->originClass = new OriginClassFile($file,OrgClassParse::PRE_FIX);
        $this->shadowClass =  new ShadowClassFile(OrgClassParse::PRE_FIX);

        $this->parseOriginFile();
    }

    protected function parseOriginFile()
    {
        $code = file_get_contents($this->originFile);

        $this->rawOrigStmts = $this->parser->parse($code);

        $this->originClassNode = $this->traverser->traverse($this->rawOrigStmts);

    }

    public function orgClassNodeDoneCB(&$node,$fullName)
    {
        $fullPath = $this->cfg['cache_dir'].'/'.str_replace('\\','/',$fullName).'.php';
        // try to keep blank and filenu
        $orgClassStr = $this->printer->printFormatPreserving(
            $node,
            $this->rawOrigStmts,
            $this->lexer->getTokens());

        $this->flushStr2File($orgClassStr,$fullPath);
        $this->classIndex[$fullName] = $fullPath;
    }

    public function shadowClassNodeDoneCB(&$node,$fullName)
    {
        $fullPath = $this->cfg['cache_dir'].'/'.str_replace('\\','/',$fullName).'.php';
        $context= $this->printer->prettyPrintFile(array($node));
        $this->flushStr2File($context,$fullPath);
        $this->classIndex[$fullName] = $fullPath;
    }

    public function flushStr2File(&$context, $fullPath)
    {
        $dir = dirname($fullPath);
        if(!is_dir($dir)){
            mkdir($dir);
        }
        file_put_contents($fullPath,$context);
    }

    public function generateAllClass():array
    {
        /// ast to source
        return $this->classIndex;
    }
}