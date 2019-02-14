<?php
/**
 * User: eeliu
 * Date: 2/13/19
 * Time: 4:35 PM
 */

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


class OrgSrcParse
{


    private $originFile;
    private $dir;
    private $lexer;
    private $parser;
    private $nameResolver;
    private $traverser;

    public $className;// app\foo\DBManager
    public $mFuncAr;
    public $originClass;
    public $shadowClass;


    public function __construct($file,$cl,$info)
    {
        assert(file_exists($file));

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

        /// no need to add nameResolver
        /// $this->traverser->addVisitor($this->nameResolver);

        $this->originClass = new OriginClassFile();
        $this->shadowClass =  new ShadowClassFile();
        $this->traverser->addVisitor(new UserCodeVisitor($this));

    }

    protected function parseOriginFile()
    {
        $code = file_get_contents($this->originFile);
        $origStmts = $this->parser->parse($code);
        $printer = new PrettyPrinter\Standard();
        $newCode = $printer->printFormatPreserving(
            $this->traverser->traverse($origStmts),
            $origStmts,
            $this->lexer->getTokens()
        );
    }

    public function getOriginClass(){

    }

    public function getShadowClass(){

    }

    public function generateAllClass():array
    {
        /// ast to source
        return array("shadow","origin");
    }
}