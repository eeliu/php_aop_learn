<?php
/**
 * User: eeliu
 * Date: 2/13/19
 * Time: 10:33 AM
 */

namespace pinpoint\Common;
use PhpParser\NodeVisitorAbstract;
use PhpParser\Node;
use pinpoint\Common\PluginParser;

class UserCodeVisitor extends NodeVisitorAbstract
{

    private $cDir;
    private $className;
    private $methodName;
    private $namespace;
    private $aliasing; // array

    public function __construct()
    {

    }

    public function enterNode(Node $node)
    {
        if($node instanceof Node\Stmt\Namespace_){
            /// set namespace
        }
        elseif ($node instanceof Node\Stmt\Use_){
            foreach ($node->uses as $use)
            {
///              $use->alias->name check function whether monitored
///              if find, drop it
            }
        }
        elseif($node instanceof Node\Stmt\Class_){
            /// rename  origin_class
            /// set shadow class name
            ///
        }
    }


    public function leaveNode(Node $node)
    {
        if ($node instanceof Node\Stmt\ClassMethod){
            /// check cared?
            ///
            /// insert it & mode into shadow class
            ///
        }elseif ($node instanceof Node\Expr\FuncCall){
            ///  Node\Name\FullyQualified => PhpParser\Node\Name
        }
        elseif ($node instanceof Node\Scalar\MagicConst)
        {
            $ret = $this->renderMagicConst($node);
            if($ret)
            {
                return $ret;
            }
        }
    }

    protected function renderMagicConst($node)
    {
        assert($node instanceof Node\Scalar\MagicConst);
        switch ($node->getName())
        {
            case '__LINE__':
                break;
            case '__FILE__':
                break;
            case '__DIR__':
                break;
            case '__FUNCTION__':
                break;
            case '__CLASS__':
                break;
            case '__METHOD__':
                break;
            case '__NAMESPACE__':
                break;
            default:
                break;
        }
        return null;
    }




}