<?php
/**
 * User: eeliu
 * Date: 1/5/19
 * Time: 6:04 PM
 */

namespace Pinpoint\Common;


class ASTManager
{
    private $ast;
    /**
     * ASTManager constructor.
     */
    public function __construct($context)
    {
    }

    public  function convert2AST($context)
    {
        $parser = (new ParserFactory)->create( Util::getMajorVersion() == '7'? (ParserFactory::PREFER_PHP7): (ParserFactory::PREFER_PHP5));
        try {
            $ast = $parser->parse($context);
        } catch (Error $error) {
            echo "Parse error: {$error->getMessage()}\n";
            return null;
        }
        return $ast;
    }

    public  function insertAliasClass($use,$as,$ast)
    {

    }

    public  function insertAliasFunction($use,$as,$ast)
    {

    }

    public function warpClassMethod($name,$onBefore,$whichException,$onException,$onEnd)
    {

    }

    public function warpFunction($name,$onBefore,$whichException,$onException,$onEnd)
    {

    }

    public function flushToFile($filePath)
    {

    }


//    // todo return node
//    public function renameFunction($old,$new)
//    {
//
//    }
//
//    public function renameMethod($old,$new,$node)
//    {
//
//    }
//
//    public function insertNodeOnBefore()
//    {
//
//    }

}