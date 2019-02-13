<?php
/**
 * User: eeliu
 * Date: 2/2/19
 * Time: 10:28 AM
 */

namespace pinpoint\Common;
use PhpParser\NodeVisitorAbstract;
use PhpParser\Node;
use pinpoint\Common\PluginParser;


class OriginClass
{

    private $prefixClassName = 'Proxied_';

    private $appendingFile = array();

    private $prefixCacheDir;

    private $node;

    private $namespace;

    private $className;

    public function getNode()
    {
        return $this->node;
    }

    public function setClassPrefix(&$node, $prefix,$cacheDir)
    {
        $this->prefixClassName = $prefix;
        $this->prefixCacheDir  = $cacheDir;
        $this->node = &$node;
    }

    public function handleNamespaceNode(&$node)
    {
        assert($node instanceof Node\Stmt\Namespace_);
        $this->namespace = $node->name->toString();
    }

    public function handleEnterClassNode(&$node)
    {
        assert($node instanceof Node\Stmt\Class_);
        $this->className = $node->name->toString();
    }

    /**rename the class Proxied_foo
     * @param $node
     */
    public function handleLeaveClassNode(&$node)
    {
        assert($node instanceof Node\Stmt\Class_);
        $node->name->name = $this->prefixClassName.$this->className;
    }

    public function handleClassMethodNode(&$node)
    {

    }

    public function handleAliasNode(&$node)
    {

    }

    public function handleFuncCallNode(&$node)
    {

    }

    public function handleMagicConstNode(&$node)
    {

    }

    //
    public function setAppendingFile($file)
    {
        if(!in_array($file,$this->appendingFile))
        {
            $this->appendingFile[] = $file;
        }
    }

}

/**
 * remove class final , rename class name
 *
 * remove class method mod ->protected
 */