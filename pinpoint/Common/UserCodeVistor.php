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
        assert($ospIns instanceof OrgSrcParse);
        $this->ospIns = $ospIns;
        $this->ignAliasAr = array();
    }

    public function enterNode(Node $node)
    {
        if($node instanceof Node\Stmt\Namespace_){

            $this->curNamespace = $node->name->toString();
            /// set namespace
            $this->ospIns->originClass->handleEnterNamespaceNode($node);
            $this->ospIns->shadowClass->handleEnterNamespaceNode($node);
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

            $this->ospIns->originClass->handleEnterClassNode($node);
            $this->ospIns->shadowClass->handleEnterClassNode($node);

        }elseif ($node instanceof Node\Stmt\ClassMethod)
        {
            $this->ospIns->originClass->handleClassEnterMethodNode($node);
            $this->ospIns->shadowClass->handleClassEnterMethodNode($node);
        }
    }


    public function leaveNode(Node $node)
    {
        if ($node instanceof Node\Stmt\ClassMethod){

            $func = $node->name->toString();
            if(array_key_exists($func,$this->ospIns->mFuncAr))
            {
                $this->ospIns->originClass->handleClassLeaveMethodNode($node);
                $this->ospIns->shadowClass->handleClassMethodNode($node);
            }

        }elseif ($node instanceof Node\Expr\FuncCall){
            $func =  $node->name->toString();
            if(array_key_exists($func,$this->ospIns->mFuncAr))
            {
                /// \print_r => print_r remove slash
                $ret = $this->ospIns->originClass->handleFuncCallNode($node);
                if($ret) {
                    return $ret;
                }
            }
        }
        elseif ($node instanceof Node\Scalar\MagicConst){
            // replace __LINE__ __DIR__
            $ret = $this->ospIns->originClass->handleMagicConstNode($node);
            if($ret)
            {
                return $ret;
            }
        }
    }

    public function afterTraverse(array $nodes)
    {
        $this->ospIns->shadowClass->handleAfterTravers($nodes);
    }




}