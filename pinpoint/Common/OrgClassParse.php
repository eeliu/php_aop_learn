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
    private $shadowClassNode;

    private $rawOrigStmts;

    private $classIndex = [];

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
        $this->printer = new PrettyPrinter\Standard();

        $this->originClass = new OriginClassFile($file,OrgClassParse::PRE_FIX);
        $this->shadowClass =  new ShadowClassFile(OrgClassParse::PRE_FIX);
        $this->traverser->addVisitor(new UserCodeVisitor($this));

        $this->parseOriginFile();
    }

    protected function parseOriginFile()
    {
        $code = file_get_contents($this->originFile);
        $this->rawOrigStmts = $this->parser->parse($code);

        $this->originClassNode = $this->traverser->traverse($this->rawOrigStmts);

    }

    public function getOriginClass(){

    }

    public function getShadowClass(){

    }

    public function orgClassNodeDoneCB(&$node,$fullName)
    {
        $fullPath = $this->cfg['plugin_path'].'/'.str_replace('\\','/',$fullName).'.php';

        $orgClassStr = $this->printer->printFormatPreserving(
            $this->traverser->traverse($this->originClassNode),
            $this->rawOrigStmts,
            $this->lexer->getTokens());

        $this->flushStr2File($orgClassStr,$fullPath);
        $this->classIndex['origin'] = $fullPath;
    }

    public function shadowClassNodeDoneCB(&$node,$fullName)
    {
        $fullPath = $this->cfg['plugin_path'].'/'.str_replace('\\','/',$fullName).'.php';

        $this->flushStr2File( $this->printer->prettyPrint($node),$fullPath);
        $this->classIndex['shadow'] = $fullPath;
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
        return array("shadow","origin");
    }
}