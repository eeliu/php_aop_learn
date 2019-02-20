<?php
/**
 * User: eeliu
 * Date: 2/13/19
 * Time: 5:32 PM
 */

namespace pinpoint\Common;


class ClassMap
{
    private $classMap;
    public $index_file_name;

    /**
     * @return mixed
     */
    public function getClassMap()
    {
        return $this->classMap;
    }

    public function insertMapping($cl,$file)
    {
        $this->classMap[$cl] = $file;
    }
    public function persistenceClassMapping($file)
    {
        /// serialize $file
    }
    public function __construct($file)
    {
        $this->index_file_name = $file;
    }
    public function debug()
    {
        print_r($this->classMap);
    }
}