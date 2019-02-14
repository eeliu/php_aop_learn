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
    protected $orgDir;
    protected $orgFile;

    public function __construct(string $prefix = 'Proxied_',$fullFile)
    {
        parent::__construct($prefix);

        $this->orgDir = dirname($fullFile);
        $this->orgFile = $fullFile;
        $this->$prefix = $prefix;
    }


    /**rename the class Proxied_foo
     * @param $node
     */
    public function handleLeaveClassNode(&$node)
    {
        assert($node instanceof Node\Stmt\Class_);
        $node->name->name = $this->prefixClassName.$this->className;
    }

    public function handleClassLeaveMethodNode(&$node)
    {

    }


    public function handleFuncCallNode(&$node)
    {

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
        if(!in_array($file,$this->appendingFile))
        {
            $this->appendingFile[] = $file;
        }
    }

    public function handleAfterTravers($nodes)
    {

    }
}

/**
 * remove class final , rename class name
 *
 * remove class method mod ->protected
 */