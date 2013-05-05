<?php
App::uses('AppController', 'Controller');
/**
 * Members Controller
 *
 * @property Member $Member
 * @property AuthComponent $Auth
 */
class MembersController extends AppController {

/**
 * Components
 *
 * @var array
 */
	var $components = array('Auth');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('admin_view');
	}
	
	function logout() {
		$this->Auth->logout();
		$this->redirect(array('controller'=>'pages','action'=>'display','home'));
	}
	
	function login() {
		/* cakephp's bug, still uses data['User'][...] even though userModel changed */
		if ($this->request->is('post')) {
			$username = $this->data['Member']['nickname'];
			$password = Security::hash($this->data['Member']['pwd'], 'md5', false);
	
			$member = $this->Member->find('first', array('conditions' => array(
				'nickname' => $username, 'pwd' => $password)));
			if($member !== false) {
				$this->Auth->login($member['Member']);
				//$this->redirect(array('controller'=>'members','action'=>'index','admin'=>true));
				$redirect = $this->Session->read('Auth.redirect');
				$this->redirect(substr($redirect,
					strlen($this->request->base)));
			}
			else {
				$this->Session->setFlash(__('Username or password is incorrect'), 'default', array(), 'auth');
			}
		}
		else {
//			debug($this->request);
		}
	}
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Member->recursive = 0;
		$this->set('members', $this->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Member->exists($id)) {
			throw new NotFoundException(__('Invalid member'));
		}
		$options = array('conditions' => array('Member.' . $this->Member->primaryKey => $id));
		$this->set('member', $this->Member->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Member->create();
			if ($this->Member->save($this->request->data)) {
				$this->Session->setFlash(__('The member has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The member could not be saved. Please, try again.'));
			}
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Member->exists($id)) {
			throw new NotFoundException(__('Invalid member'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Member->save($this->request->data)) {
				$this->Session->setFlash(__('The member has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The member could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Member.' . $this->Member->primaryKey => $id));
			$this->request->data = $this->Member->find('first', $options);
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @throws MethodNotAllowedException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Member->id = $id;
		if (!$this->Member->exists()) {
			throw new NotFoundException(__('Invalid member'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Member->delete()) {
			$this->Session->setFlash(__('Member deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Member was not deleted'));
		$this->redirect(array('action' => 'index'));
	}

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Member->recursive = 0;
		$this->set('members', $this->paginate());
	}

/**
 * admin_view method: Used as debug function
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
$help=<<<'HELP'
/admin/members/view/?, 
?=request, response
auth.user, or [default] a session variable
HELP;
		if (preg_match('/^[0-9]+$/',$id)) {
			if (!$this->Member->exists($id)) {
				throw new NotFoundException(__('Invalid member'));
			}
			$options = array('conditions' => array('Member.' . $this->Member->primaryKey => $id));
			$mem = $this->Member->findById(1);
			$this->set('value', $mem);//$this->Member->find('first', $options));
		}
		else {
			$this->set('value', $help);
			if ($id=='request') {
				debug($this->request);
			}
			else if ($id=='response') {
				debug($this->response);
			}
			else if ($id=='auth.user') {
				debug($this->Auth->user());
			}
			else {
				debug($this->Session->read($id));
			}
		}
		//$this->set('value', Security::hash('mathew', 'md5', false));
		//debug($this->Auth->user());
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Member->create();
			if ($this->Member->save($this->request->data)) {
				$this->Session->setFlash(__('The member has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The member could not be saved. Please, try again.'));
			}
		}
	}

/**
 * admin_edit method
 * Member.print borrowed as 'reset password' selector
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		$options = array('conditions' => array('Member.' . $this->Member->primaryKey => $id));
		$member = $this->Member->find('first', $options);
		if ($this->Member==null) {//->exists($id)) {
			throw new NotFoundException(__('Invalid member'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			// overwrite readonly fields
			$this->request->data('Member.id', $member['Member']['id'])
				->data('Member.name', $member['Member']['name']);
			// reset password?
			if ($this->data['Member']['print']) {
				// handled by beforeSave $password = Security::hash($this->data['Member']['pwd'], 'md5', false);
				$this->request->data('Member.pwd', $this->data['Member']['pwd']);
			}
			else {
				unset($this->data['Member']['pwd']);
			}
			//debug($this->request->data);
			//return;
			if ($this->Member->save($this->request->data)) {
				$this->Session->setFlash(__('The member has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The member could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Member.' . $this->Member->primaryKey => $id));
			$this->request->data = $member;
			// blank password
			$this->request->data('Member.pwd', '')
			->data('Member.print','');
		}
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @throws MethodNotAllowedException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->Member->id = $id;
		if (!$this->Member->exists()) {
			throw new NotFoundException(__('Invalid member'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Member->delete()) {
			$this->Session->setFlash(__('Member deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Member was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
	
	function suggest() {
		Configure::write('debug', 0);
		$val =  $this->params['url']['val'];
		$raw = $this->Member->find('list',array(
			'conditions'=>array('Member.name LIKE'=>$val.'%'),
			'order'=>'Member.name'
		));
		//debug($raw);
		foreach ($raw as $id=>$name) {
			unset($hash);
			$hash['When']=$val;
			$hash['Value'] = $id;
			$hash['Text'] = $name;
			$arr[]=$hash;
		}
		$this->set('results', $arr);
		$this->set('_serialize','results');
	}
}
