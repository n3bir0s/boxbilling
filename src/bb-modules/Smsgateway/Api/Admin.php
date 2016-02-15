<?php
/**
 * BoxBilling
 *
 * @copyright BoxBilling, Inc (http://www.boxbilling.com)
 * @license   Apache-2.0
 *
 * Copyright BoxBilling, Inc
 * This source file is subject to the Apache-2.0 License that is bundled
 * with this source code in the file LICENSE
 */

/**
 * Example module Admin API
 * 
 * API can be access only by admins
 */

namespace Box\Mod\SMSGateway\Api;

class Admin extends \Api_Abstract
{
    /**
     * Return list of example objects
     * 
     * @return string[]
     */
    public function get_something($data)
    {
        $result = array(
            'apple',
            'google',
            'facebook',
        );

        if(isset($data['microsoft'])) {
            $result[] = 'microsoft';
        }
        
        return $result;
    }
		
    public function plugin_get_pairs(array $data)
    {
        $plugins = $this->getService()->getGatewaysPlugins();
        $result = array();
        foreach($plugins as $plugin) {
            $filename = $plugin['filename'];
            $result[strtolower($filename)] = $filename;
        }
        return $result;
    }	


    /**
     * Edit Gateway plugin settings
     * 
     * @param string $code - theme code
     * @return bool
     */
    public function select($data)
    {
        $required = array(
            'code'    => 'Plugin code is missing',
        );
        $this->di['validator']->checkRequiredParamsForArray($required, $data);

/*
        $theme = $this->getService()->getTheme($data['code']);

        $systemService = $this->di['mod_service']('system');
        if($theme->isAdminAreaTheme()) {
            $systemService->setParamValue('admin_theme', $data['code']);
        } else {
            $systemService->setParamValue('theme', $data['code']);
        }
*/


        $this->di['logger']->info('Plugin settings changed');
        return true;
    }		
}