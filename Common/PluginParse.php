<?php
/**
 * User: eeliu
 * Date: 1/5/19
 * Time: 2:35 PM
 */

namespace Pinpoint\Common;

use Exception;
use PhpParser\ParserFactory;
use PhpParser\Error;
use PhpParser\NodeDumper;
use PhpParser\BuilderFactory;
use PhpParser\Node;
use PhpParser\NodeFinder;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use Pinpoint\Common\Util;


class PluginParse
{
    private $classAnnotation ;
    private $onBeforeAnnotation ;
    private $onEndAnnotation;
    private $onExpAnnotation ;
    private $interceptorNode ; // [ onbefore, catch exception  ]

    private $fileContext;
    private $ast;
    /**
     * PluginParse constructor.
     */
    public function __construct($fullname)
    {
        if(!file_exists($fullname))
        {
            throw new Exception('['.$fullname.'] file not exist');
        }

        $this->fileContext = file_get_contents($fullname);

        $this->ast = Util::convert2AST($this->fileContext);

    }



    private function parseAnnotation()
    {

    }


}