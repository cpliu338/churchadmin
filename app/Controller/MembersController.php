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
		$this->Auth->allow('admin_view','login2');
	}

	function logout() {
		$this->Auth->logout();
		$this->redirect(array('controller'=>'pages','action'=>'display','home'));
	}
	
	function login() {
		if ($this->request->is('post')) {
			$ldaprdn  = 'cn=manager,ou=Internal,dc=system,dc=lan';     // ldap rdn or dn
			$ldappass = 'jMSL5KNZtM+O8RB+';  // associated password
			putenv('LDAPTLS_REQCERT=never');

			// connect to ldap server
			$ldapconn = @ldap_connect("ldaps://192.168.11.224",636);
			if (!$ldapconn) {
				$this->Session->setFlash(__('Cannot connect to directory server'));
				$this->render('login');
				return;
			}

			// binding to ldap server
			$ldapbind = @ldap_bind($ldapconn, $ldaprdn, $ldappass);

			// verify binding
			if (!$ldapbind) {
				$this->Session->setFlash(__("Cannot bind to directory server, using internal login"));
				//$this->render('login');
				$this->login2(); // if cannot connect ldap, try internal
				return;
			}
			$username = $this->data['Member']['nickname'];

			$dn = "dc=system,dc=lan";
			$filter="(uid=$username)";
			//debug($filter);
			$justthese = array("cn", "sn", "givenname", "clearSHA1Password");

			$sr=@ldap_search($ldapconn, $dn, $filter, $justthese) or die("Cannot search");

			if (!$sr) {
				$this->Session->setFlash(__("Cannot search $filter"));
				$this->render('login');
				return;
			}
			$data = @ldap_get_entries($ldapconn, $sr);
			if (!$data || $data['count']!=1) {
				$this->Session->setFlash(__("Cannot find $username"));
				$this->render('login');
				return;
			}
			$password = Security::hash($this->data['Member']['pwd'], 'sha1', false);
			// 77447f779a24f225d070c6644d769f43943561ee
			$entry = $data[0];
			if ($entry['clearsha1password'][0] != $password) {
				$this->Session->setFlash(__("Wrong password"));
				$this->render('login');
				return;
			}
			$member = $this->Member->findByNickname($username);
			// debug($member);
				// $this->render('login');
			// return;
			// $member = $this->Member->find('first', array('conditions' => array(
				// 'nickname' => $username, 'pwd' => $password)));
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
			$this->render('login');
		}
	}

	function login2() {
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
				$this->Session->setFlash(__('Internal DB username or password is incorrect'), 'default', array(), 'auth');
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
		$filter = '%';
		if ($this->request->is('post')) {
			$filter = '%'.trim($this->data['Member']['name']).'%';
		}
		$this->set('members', $this->paginate('Member',
			array('name LIKE'=>$filter)
			));
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
		else {
			$raw = $this->Member->find('all',array(
				'fields'=>'DISTINCT (Member.groupname) AS groupname2',
				'order'=>'groupname2'	
				));
			$groups = array();
			foreach ($raw as $entry) {
				$name = $entry['Member']['groupname2'];
				$groups[$name] = $name;
			}
			$this->set('groups',
				//$this->Member->find('list',array('limit'=>5)				
					$groups
			);
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
?=request, response,
user, or [default] a session variable
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
			else if ($id=='user') {
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
		if (!$this->isLevelEnough(95)) {
			throw new ForbiddenException(__('Forbidden'));
		}
		$options = array('conditions' => array('Member.' . $this->Member->primaryKey => $id));
		$member = $this->Member->find('first', $options);
		if ($this->Member==null) {//->exists($id)) {
			throw new NotFoundException(__('Invalid member'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			// overwrite readonly fields
			$this->request->data('Member.id', $member['Member']['id']);
				//->data('Member.name', $member['Member']['name']);
			// reset password?
			if ($this->data['Member']['print']) {
				// handled by beforeSave $password = Security::hash($this->data['Member']['pwd'], 'md5', false);
				$this->request->data('Member.pwd', $this->data['Member']['pwd']);
			}
			else {
				unset($this->request->data['Member']['pwd']);
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
