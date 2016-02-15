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
     * Create new redirect
     * 
     * @param string $path - redirect path
     * @param string $target - redirect target
     * 
     * @return int redirect id
     */
    public function config_save($data)
    {
        $required = array(
            'api_key'   => 'Invalid or empty API KEY',
            'username' => 'Invalid or empty Email/Username',
            'client_field' => 'Invalid or empty Client field',
        );
        $this->di['validator']->checkRequiredParamsForArray($required, $data);
        
        $plugin = $this->di['db']->dispense('extension_meta');
        $plugin->extension = 'mod_smsgateway';
        $plugin->rel_type = 'settings';
        $plugin->rel_id = $data['plugin_code'];
        $plugin->meta_key = 'config';
        $plugin->meta_value = json_encode($data);
        $plugin->created_at = date('Y-m-d H:i:s');
        $plugin->updated_at = date('Y-m-d H:i:s');
        $this->di['db']->store($plugin);
        
        $id = $plugin->id;
        
        $this->di['logger']->info('Configured pluging #%s', $id);
        return (int)$id;
    }	
		
    public function config_get($data){
        $sql="SELECT meta_value
            FROM extension_meta
            WHERE extension = 'mod_smsgateway'
            AND rel_id = :code
            LIMIT 1;";
						
				$plugin_config = $this->di['db']->getCell($sql, array('code'=>$data['code']));
        return json_decode($plugin_config);
				
    }	
		
	
}