<?php
/**
 * User: eeliu
 * Date: 2/2/19
 * Time: 2:37 PM
 */

namespace pinpoint\Common;
use PhpParser\NodeVisitorAbstract;
use PhpParser\Node;
use pinpoint\Common\PluginParser;

class PluginVisitor extends NodeVisitorAbstract
{
    private $iParser;
    public function __construct($parser)
    {
        if( $parser instanceof PluginParser)
        {
            $this->iParser = $parser;
            return ;
        }
        throw new \Exception("illegal input");
    }


    private function loadCommentFunc(&$node,$mode)
    {
       foreach( $node->getComments() as &$doc)
       {
            $funArray = Util::parseUserFunc(trim($doc->getText()));
            foreach ($funArray as $func)
            {
                $this->iParser->insertFunc($func,$mode);
            }
        }
    }

    public function leaveNode(Node $node)
    {
        // todo ignore pinpoint namespace
        if ($node instanceof Node\Stmt\Namespace_) {
            $this->iParser->setNamespace(trim($node->name->toString()));
        }
        elseif($node instanceof Node\Stmt\Class_)
        {
            $cname = $node->name->toString();
            $this->iParser->setClassName($cname);
            $this->loadCommentFunc($node, PluginParser::ALL);
        }
        elseif($node instanceof Node\Stmt\ClassMethod)
        {
            // fetch method level
            $name = $node->name->toString();
            $node->getComments();
            switch($name)
            {
                case "onBefore":
                    $this->loadCommentFunc($node, PluginParser::BEFORE);
                    break;
                case "onEnd":
                    $this->loadCommentFunc($node, PluginParser::END);
                    break;
                case "onException":
                    $this->loadCommentFunc($node, PluginParser::EXCEPTION);
                    break;
                default:
                    // do nothing
            }

        }
    }
}