<?php
/**
 * User: eeliu
 * Date: 1/5/19
 * Time: 5:22 PM
 */

namespace Pinpoint\Common;

use PhpParser\Parser;
use PhpParser\ParserFactory;
use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\TryCatch;
use PhpParser\Node\Stmt\Catch_;


abstract class SourceScanner
{
    abstract protected function findClass($class);
    abstract protected function findFunction($func);
}