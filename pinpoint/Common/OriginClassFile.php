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
use pinpoint\Common\ClassFile;

class OriginClassFile extends ClassFile
{
    /** dir assign to __DIR__
     * @var string
     */
    protected $orgDir;
    protected $orgFile;

    public function __construct($fullFile,$prefix = 'Proxied_')
    {
        parent::__construct($prefix);

        $this->orgDir = dirname($fullFile);
        $this->orgFile = $fullFile;
        $this->$prefix = $prefix;
    }


    /** rename the class Proxied_foo
     * @param $node
     */
    public function handleLeaveClassNode(&$node)
    {
        assert($node instanceof Node\Stmt\Class_);

        $node->name->name = $this->prefix.$node->name->name;
        $this->className = $this->namespace.'\\'.$node->name->name;
        if($node->flags & Node\Stmt\Class_::MODIFIER_FINAL)
        {
            /// remove FINAL flag
            $node->flags = $node->flags & ( ~(Node\Stmt\Class_::MODIFIER_FINAL) );
        }
    }

    public function handleClassLeaveMethodNode(&$node,&$info)
    {
        assert($node instanceof Node\Stmt\ClassMethod);
        if($node->flags &  Node\Stmt\Class_::MODIFIER_PRIVATE)
        {
            $node->flags = $node->flags &(~Node\Stmt\Class_::MODIFIER_PRIVATE);
        }
    }


    public function handleFuncCallNode(&$node)
    {
        /// todo remove slash,
        /// \print_r ->print_r
        ///
    }

    public function handleMagicConstNode(&$node)
    {
        switch ($node->getName())
        {
            case '__LINE__':
                return new Node\Scalar\LNumber($node->getLine());
                break;
            case '__FILE__':
                return new Node\Scalar\String_($this->orgFile);
                break;
            case '__DIR__':
                return new Node\Scalar\String_($this->orgDir);
                break;
            case '__FUNCTION__':
                return new Node\Scalar\String_($this->classMethod);
                break;
            case '__CLASS__':
                return new Node\Scalar\String_($this->className);
                break;
            case '__METHOD__':
                return new Node\Scalar\String_($this->classMethod);
                break;
            case '__NAMESPACE__':
                return new Node\Scalar\String_($this->namespace);
                break;
            default:
                break;
        }

    }

    //
    public function setAppendingFile($file)
    {
        // modify the namespace
        if(!in_array($file,$this->appendingFile))
        {
            $this->appendingFile[] = $file;
        }
    }

    public function handleLeaveNamespace(&$nodes,&$mFuncAr)
    {
        /// todo
        ///  render header file: insert namespace
        ///  add require
        ///
//        $this->fileNode = $nodes;
//
//        foreach ($mFuncAr as $fun=>$info)
//        {
//            /// render header file: insert namespace
//
//            /// add require
//        }
    }

    public function handleAfterTravers(&$nodes,&$mFuncAr)
    {
        return $nodes;
    }
}
