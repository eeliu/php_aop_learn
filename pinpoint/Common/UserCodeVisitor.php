<?php
/**
 * User: eeliu
 * Date: 2/13/19
 * Time: 10:33 AM
 */

namespace pinpoint\Common;

use PhpParser\NodeVisitorAbstract;
use PhpParser\Node;
use PhpParser\NodeTraverser;

class UserCodeVisitor extends NodeVisitorAbstract
{

    private $ignAliasAr;

    protected $ospIns;
    private $curNamespace;
    private $curClass;

    public function __construct($ospIns)
    {
        assert($ospIns instanceof OrgClassParse);
        $this->ospIns = $ospIns;
        $this->ignAliasAr = array();
        $this->curClass = $ospIns->className;
    }

    public function enterNode(Node $node)
    {
//        echo "enter".$node->getType()."\n";
        if($node instanceof Node\Stmt\Namespace_){

            $this->curNamespace = $node->name->toString();
            /// set namespace
            $this->ospIns->shadowClass->handleEnterNamespaceNode($node);
            $this->ospIns->originClass->handleEnterNamespaceNode($node);
        }
        elseif ($node instanceof Node\Stmt\Use_){

            foreach ($node->uses as $use)
            {
                $this->ignAliasAr[] = $use->name->toString();
            }
        }
        elseif ($node instanceof Node\Stmt\Class_){

            if( $this->curNamespace.'\\'.$node->name->toString() != $this->curClass)
            {
                // ignore uncared
                echo "NodeTraverser::DONT_TRAVERSE_CHILDREN @".$this->curClass;
                return NodeTraverser::DONT_TRAVERSE_CHILDREN;
            }

            $this->ospIns->shadowClass->handleEnterClassNode($node);
            $this->ospIns->originClass->handleEnterClassNode($node);

        }elseif ($node instanceof Node\Stmt\ClassMethod)
        {
            $this->ospIns->shadowClass->handleClassEnterMethodNode($node);
            $this->ospIns->originClass->handleClassEnterMethodNode($node);
        }
        elseif ( $node instanceof Node\Stmt\Return_)
        {
            $this->ospIns->shadowClass->handleReturnNode($node);
        }
    }


    public function leaveNode(Node $node)
    {
//        echo "leave".$node->getType()."\n";
        if ($node instanceof Node\Stmt\ClassMethod){
            $func = trim( $node->name->toString());
            if(array_key_exists($func,$this->ospIns->mFuncAr))
            {
                $this->ospIns->shadowClass->handleClassLeaveMethodNode($node,$this->ospIns->mFuncAr[$func]);
                $this->ospIns->originClass->handleClassLeaveMethodNode($node,$this->ospIns->mFuncAr[$func]);
                unset( $this->ospIns->mFuncAr[$func] );
            }
        }elseif ($node instanceof Node\Expr\FuncCall){
            /// todo parse internal funcCall
        }
        elseif ($node instanceof Node\Scalar\MagicConst){
            /// replace __LINE__ __DIR__
            $ret = $this->ospIns->originClass->handleMagicConstNode($node);
            if($ret){
                return $ret;
            }
        }elseif ($node instanceof Node\Stmt\Namespace_){
            /// todo ending the np
        }
        elseif ($node instanceof Node\Stmt\Class_){
           return  $this->ospIns->originClass->handleLeaveClassNode($node);
        }
    }

    public function afterTraverse(array $nodes)
    {
        $node = $this->ospIns->shadowClass->handleAfterTravers($nodes,
            $this->ospIns->mFuncAr);
        $this->ospIns->shadowClassNodeDoneCB($node,$this->ospIns->shadowClass->className);

        $node = $this->ospIns->originClass->handleAfterTravers($nodes,
            $this->ospIns->mFuncAr);
        $this->ospIns->orgClassNodeDoneCB($node,$this->ospIns->originClass->className);

    }

}