<?php
/**
 * User: eeliu
 * Date: 2/13/19
 * Time: 10:33 AM
 */

namespace pinpoint\Common;

use PhpParser\NodeVisitorAbstract;
use PhpParser\Node;


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
    }

    public function enterNode(Node $node)
    {
        if($node instanceof Node\Stmt\Namespace_){

            $this->curNamespace = $node->name->toString();
            /// set namespace
            $this->ospIns->shadowClass->handleEnterNamespaceNode($node);
            $this->ospIns->originClass->handleEnterNamespaceNode($node);
        }
        elseif ($node instanceof Node\Stmt\Use_){

            foreach ($node->uses as $use)
            {
                $this->ignAliasAr[] = $use->alias->toString();
            }
        }
        elseif ($node instanceof Node\Stmt\Class_){

            if( $this->curNamespace.'\\'.$node->name->toString() != $this->curClass)
            {
                // ignore uncared
                echo "NodeTraverser::DONT_TRAVERSE_CHILDREN @".$node->name->toString();
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
    }

    public function afterTraverse(array $nodes)
    {
        $this->ospIns->shadowClassNodeDoneCB($this->ospIns->shadowClass->handleAfterTravers($nodes,
            $this->ospIns->mFuncAr),$this->ospIns->shadowClass->className);

        $this->ospIns->orgClassNodeDoneCB($this->ospIns->originClass->handleAfterTravers($nodes,
            $this->ospIns->mFuncAr),$this->ospIns->originClass->className);
//        $this->ospIns->
    }




}