<?php
/**
 * User: eeliu
 * Date: 2/3/19
 * Time: 2:09 PM
 */

namespace pinpoint\Common;
use pinpoint\Common\PluginParser;

class PluginsManager
{
    /*
     * (
     *
     *  'app\foo':('func1'=>[mode] = 1,'func2'=>[mode] = 1,'func3'=>[mode] = 2) \\ func3 system function
     * )
     */
    private $PluginsClass = array();

    public function registerPlugins($plugins)
    {
        if(!$plugins instanceof PluginParser)
        {
            // todo
        }
    }
}