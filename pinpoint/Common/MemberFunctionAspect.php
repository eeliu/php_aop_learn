<?php
/**
 * User: eeliu
 * Date: 2/1/19
 * Time: 2:16 PM
 */

namespace pinpoint\Common;
use pinpoint\Common\Aspect;


class MemberFunctionAspect extends Aspect
{

    public function __construct($name,$plugins_name)
    {
        parent::__construct($name);

        // \app\Foo::foo_p1

        // load origin file

        // parse the define

    }

    public function onBefore()
    {
        // TODO: Implement onBefore() method.

    }

    public function onEnd()
    {
        // TODO: Implement onEnd() method.
    }

    public function onException()
    {
        // TODO: Implement onException() method.
    }


}