<?php
/**
 * User: eeliu
 * Date: 2/2/19
 * Time: 10:29 AM
 */

namespace pinpoint\Common;

use PhpParser\Parser;
use PhpParser\ParserFactory;
use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeVisitorAbstract;
use PhpParser\NodeTraverser;
use pinpoint\Common\PluginVisitor;

class PluginParser
{
    private $funcArray;


    private $namespace;
    private $className;
    private $pluginsFile;

    const BEFORE=0x1;
    const END=0x2;
    const EXCEPTION=0x4;
    const ALL=0x7;
    /**
     * @return mixed
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @return mixed
     */
    public function getFuncArray()
    {
        return $this->funcArray;
    }

    /**
     * @param mixed $namespace
     */
    public function setNamespace($namespace): void
    {
        $this->namespace = $namespace;
    }

    /**
     * @return mixed
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @param mixed $className
     */
    public function setClassName($className): void
    {
        $this->className = $className;
    }

    public function __construct($classFile)
    {
        if( !file_exists($classFile))
        {
            throw new \Exception($classFile.": File not find");
        }
        $this->pluginsFile = $classFile;
        $this->funcArray = array();
    }

    public function run()
    {
        // todo , need add php5? php7 include php5 ?
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $nodes = $parser->parse(file_get_contents($this->pluginsFile));
        $nodeTraverser = new NodeTraverser;
        $nodeTraverser->addVisitor(new PluginVisitor($this));
        $nodeTraverser->traverse($nodes);
    }

    public function insertFunc($funcName, $mode)
    {
        if(array_key_exists($funcName,$this->funcArray))
        {
            $this->funcArray[$funcName]['mode'] |= $mode;
            return ;
        }
        list($Cl,$func) = explode("::",$funcName);

        $this->funcArray[$funcName] = array(
            'mode'=> $mode,
            'class'=>$Cl,
            'func'=>$func
        );

    }

}