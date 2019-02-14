<?php
/**
 * User: eeliu
 * Date: 2/14/19
 * Time: 11:36 AM
 */

namespace pinpoint\Common;

use PhpParser\Node;

class ClassFile
{
    public $appendingFile = array();

    public $node;

    protected $prefix;

    public $namespace;

    public $className;

    public $classMethod;

    public $funcName; // only for __FUNCTION__

    protected $dir;

    public function __construct($prefix)
    {
        $this->prefix = $prefix;
    }

    public function getNode()
    {
        return $this->node;
    }

    public function handleEnterNamespaceNode(&$node)
    {
        assert($node instanceof Node\Stmt\Namespace_);
        $this->namespace = $node->name->toString();
    }

    public function handleEnterClassNode(&$node)
    {
        assert($node instanceof Node\Stmt\Class_);
        $this->className = $this->namespace.'\\'.$node->name->toString();
    }

    public function handleClassEnterMethodNode(&$node)
    {
        assert($node instanceof Node\Stmt\ClassMethod);
        $this->funcName = $node->name->toString();
        $this->classMethod =$this->className.'::'.$this->funcName;
    }
}
