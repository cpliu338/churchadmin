<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	var $components = array('Auth',
		'Session');
        
    var $numberOptions = array(
        'places' => 2,
        'escape' => false,
        'before' => '',
        'decimals' => '.',
        'thousands' => ','
    );
    
	public function isLevelEnough($lev) {
		$user = $this->Auth->user();
		return $user['level']>=$lev;
	}
	
	public function beforeFilter() {
		// Use MD5 no salt so that members table can be used in different servers
		Security::setHash('md5');
 		$this->Auth->userModel='Member';
		$this->Auth->fields = array(
			'username'=>'nickname','password'=>'pwd');
		$this->Auth->loginAction = array('admin'=>false,'controller'=>'members','action'=>'login');
		$this->Auth->loginRedirect = array('admin'=>true,'controller'=>'members','action'=>'index');
		
		// default title, verbose in title, i18n in header <h2>
		$this->set('title_for_layout', "{$this->request->action} {$this->request->controller}");
		
		// Output toggle admin link if level is enough
		$this->set('toggle',$this->isLevelEnough(99));
	}
}
