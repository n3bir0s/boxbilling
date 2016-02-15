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
 * This file connects BoxBilling admin area interface and API
 * Class does not extend any other class
 */

namespace Box\Mod\SMSGateway\Controller;

class Admin implements \Box\InjectionAwareInterface
{
    protected $di;

    /**
     * @param mixed $di
     */
    public function setDi($di)
    {
        $this->di = $di;
    }

    /**
     * @return mixed
     */
    public function getDi()
    {
        return $this->di;
    }

    /**
     * This method registers menu items in admin area navigation block
     * This navigation is cached in bb-data/cache/{hash}. To see changes please
     * remove the file
     * 
     * @return array
     */
    public function fetchNavigation()
    {
        return array(
            'group'  =>  array(
                'index'     						=> 1500,                // menu sort order
                'location'  					=>  'smsgateway',          // menu group identificator for subitems
                'label'     						=> 'SMS Gateway',    // menu group title
                'class'     						=> 'smsgateway',           // used for css styling menu item
                'sprite_class'     	=> 'dark-sprite-icon sprite-buzz',  		// used for css styling menu item
            ),
            'subpages'=> array(
                array(
                    'location'  			=> 'smsgateway', // place this module in extensions group
                    'label'     				=> 'Configuration',
                    'index'     				=> 10,
                    'uri'       					=> $this->di['url']->adminLink('extension/settings/smsgateway'),
                    'class'     				=> '',
                ),
                array(
                    'location'  			=> 'smsgateway', // place this module in extensions group
                    'label'     				=> 'SMS Gateway Plugins',
                    'index'     				=> 20,
                    'uri'       					=> $this->di['url']->adminLink('smsgateway/plugins'),
                    'class'     				=> '',
                ),
                array(
                    'location'  			=> 'smsgateway', // place this module in extensions group
                    'label'     				=> 'About SMS Gateway notifications',
                    'index'     				=> 30,
                    'uri'       					=> $this->di['url']->adminLink('smsgateway/about'),
                    'class'     				=> '',
                ),								
            ),
        );
    }

    /**
     * Methods maps admin areas urls to corresponding methods
     * Always use your module prefix to avoid conflicts with other modules
     * in future
     *
     *
     * @example $app->get('/example/test',      'get_test', null, get_class($this)); // calls get_test method on this class
     * @example $app->get('/example/:id',        'get_index', array('id'=>'[0-9]+'), get_class($this));
     * @param \Box_App $app
     */
    public function register(\Box_App &$app)
    {
        $app->get('/smsgateway',                 								'get_index', array(), get_class($this));
        $app->get('/smsgateway/test',         								'get_test', array(), get_class($this));
        $app->get('/smsgateway/plugins',    								'get_plugins', array(), get_class($this));
        $app->get('/smsgateway/plugin/:code', 					'get_plugin', array('code'=>'[a-z0-9-_]+'), get_class($this));   // need to check and fix 
				$app->get('/smsgateway/about',      								'get_about', array(), get_class($this));
        $app->get('/smsgateway/user/:id',   								'get_user', array('id'=>'[0-9]+'), get_class($this));
        $app->get('/smsgateway/api',          								'get_api', array(), get_class($this));
    }

    public function get_plugin(\Box_App $app, $code)
    {
        // always call this method to validate if admin is logged in
        $this->di['is_admin_logged'];

				$plugin_config = array();
					
        $params = array();
        $params['code'] = $code;
        $params['pluginconfig'] = $plugin_config;
				
        return $app->render('mod_smsgateway_settings', $params);
    }		
		
    public function get_index(\Box_App $app)
    {
        // always call this method to validate if admin is logged in
        $this->di['is_admin_logged'];
        return $app->render('mod_smsgateway_index');
    }

    public function get_test(\Box_App $app)
    {
        // always call this method to validate if admin is logged in
        $this->di['is_admin_logged'];

        $params = array();
        $params['youparamname'] = 'yourparamvalue';

        return $app->render('mod_smsgateway_index', $params);
    }
 
		public function get_plugins(\Box_App $app)
    {
        // always call this method to validate if admin is logged in
        $this->di['is_admin_logged'];

        $params = array();
        $params['plugins'] = array ( 
							'title' =>'plugins list with settings', 
				);

        return $app->render('mod_smsgateway_index', $params);
    }

    public function get_about(\Box_App $app)
    {
        // always call this method to validate if admin is logged in
        $this->di['is_admin_logged'];
        return $app->render('mod_smsgateway_about');
    }		
		
    public function get_user(\Box_App $app, $id)
    {
        // always call this method to validate if admin is logged in
        $this->di['is_admin_logged'];

        $params = array();
        $params['userid'] = $id;
        return $app->render('mod_smsgateway_index', $params);
    }
    
    public function get_api(\Box_App $app, $id)
    {
        // always call this method to validate if admin is logged in
        $api = $this->di['api_admin'];
        $list_from_controller = $api->smsgateway_get_something();

        $params = array();
        $params['api_example'] = true;
        $params['list_from_controller'] = $list_from_controller;

        return $app->render('mod_smsgateway_index', $params);
    }
}