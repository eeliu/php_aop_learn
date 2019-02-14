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

class ShadowClassFile extends ClassFile
{
    private $factory;

    private $namespaceNode;
    private $classNode;


    public function __construct($prefix)
    {
        parent::__construct($prefix);
        $this->factory= new BuilderFactory();
    }


    public function handleEnterNamespaceNode(&$node)
    {
        parent::handleEnterNamespaceNode($node);
        assert($node instanceof Node\Stmt\Namespace_);

        $this->namespaceNode = $this->factory->namespace($node->name->toString());
    }

    public function handleEnterClassNode(&$node)
    {
        parent::handleEnterClassNode($node);
        assert($node instanceof Node\Stmt\Class_);

        $this->classNode = $this->factory->class($node->name->toString())->extend($this->prefix.'_'.$node->name->toString());

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
        }

    }

    public function handleClassEnterMethodNode(&$node)
    {
        parent::handleClassEnterMethodNode($node);
        assert($node instanceof Node\Stmt\ClassMethod);
    }


    private function addAliasing($className)
    {

    }

    public function handleClassLeaveMethodNode(&$node)
    {

    }

    public function handleAfterTravers(&$nodes)
    {

    }

}