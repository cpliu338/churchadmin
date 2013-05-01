<?php
App::uses('AppController', 'Controller','Number');
/**
 * Accounts Controller
 *
 * @property Account $Account
 */
class AccountsController extends AppController {
	
	public $helpers = array('Form', 'Html', 'Js', 'Totalize');
    
    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('numberOptions', $this->numberOptions);
    }

/**
 * index method
 *
 * @return void
 */
	public function index() {
            $this->loadModel('Entry');
            $this->set('yearStart', $this->Entry->getYearStart($this->Entry->getStartDate()));
            $this->set('yearEnd', $this->Entry->getYearEnd($this->Entry->getStartDate()));
            if (array_key_exists('under', $this->request->query) && !empty($this->request->query['under'])) {
                $under = $this->request->query['under'];
                if (substr($under, -1)==='0')
                    $pattern = substr($under, 0, strlen($under)-1) . '%';
                else
                    $pattern = $under;
            }
            else {
                $under = '';
                $pattern = '_0';
            }
            $this->Account->recursive = 0;
            $this->set('accounts', $this->Account->find('all', array(
                'conditions' => array('Account.code LIKE'=> $pattern)
            )));
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
            if (!$this->Account->exists($id)) {
                    throw new NotFoundException(__('Invalid account'));
            }
            $this->loadModel('Entry');
            if ($this->request->is('get')) {
                $date1 = $this->Entry->getStartDate();
                $this->request->data('Entry.date1', $date1);
            }
            else {
                $date1 = $this->request->data('Entry.date1');
                $this->Entry->setStartDate($date1);
            }
            $options = array('conditions' => array('Account.' . $this->Account->primaryKey => $id));
            $account = $this->Account->find('first', $options);
            $this->set('account', $account);
            // no joins needed
            $this->Entry->recursive = 0;
            $yearStart = $this->Entry->getYearStart($date1);
            $this->set('yearStart',$yearStart);
            $pattern = $this->Account->getPatternUnder($account['Account']['code']);
            $this->set('entries', $this->Entry->find('all', array('limit' => 25, 'conditions'=>array(
                    'Account.code LIKE'=>$pattern,
                    'Entry.date1 <='=>$this->Entry->getEndDate($date1),
                    'Entry.date1 >='=>$date1),
                'order'=>array('Entry.date1')
                    )));
            $this->set('broughtForward', $this->Entry->find('first', array('fields' => 'SUM(amount) AS total', 'conditions' => array(
                'Account.code LIKE' => $pattern,
                'Entry.date1 >' => $yearStart,
                'Entry.date1 <' => $date1)
            )));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Account->create();
			if ($this->Account->save($this->request->data)) {
				$this->Session->setFlash(__('The account has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The account could not be saved. Please, try again.'));
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
		if (!$this->Account->exists($id)) {
			throw new NotFoundException(__('Invalid account'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Account->save($this->request->data)) {
				$this->Session->setFlash(__('The account has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The account could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Account.' . $this->Account->primaryKey => $id));
			$this->request->data = $this->Account->find('first', $options);
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
		$this->Account->id = $id;
		if (!$this->Account->exists()) {
			throw new NotFoundException(__('Invalid account'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Account->delete()) {
			$this->Session->setFlash(__('Account deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Account was not deleted'));
		$this->redirect(array('action' => 'index'));
	}

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Account->recursive = 0;
		$this->set('accounts', $this->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Account->exists($id)) {
			throw new NotFoundException(__('Invalid account'));
		}
		$options = array('conditions' => array('Account.' . $this->Account->primaryKey => $id));
		$this->set('account', $this->Account->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Account->create();
			if ($this->Account->save($this->request->data)) {
				$this->Session->setFlash(__('The account has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The account could not be saved. Please, try again.'));
			}
		}
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->Account->exists($id)) {
			throw new NotFoundException(__('Invalid account'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Account->save($this->request->data)) {
				$this->Session->setFlash(__('The account has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The account could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Account.' . $this->Account->primaryKey => $id));
			$this->request->data = $this->Account->find('first', $options);
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
		$this->Account->id = $id;
		if (!$this->Account->exists()) {
			throw new NotFoundException(__('Invalid account'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Account->delete()) {
			$this->Session->setFlash(__('Account deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Account was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
	
	function suggest() {
		Configure::write('debug', 0);
		$val =  $this->params['url']['val'];
		$raw = $this->Account->find('all', array(
			'conditions'=>array('Account.code LIKE'=>$val.'%',
				'NOT'=>array('details'=>'deadmark')),
			'order'=>'code'));
foreach ($raw as $acc) {
	if (substr($acc['Account']['code'], -1) == '0')
		continue;
	unset($hash);
	$hash['When']=$val;
	$hash['Value'] = $acc['Account']['id'];
	$hash['Text'] = $acc['Account']['name_chi'];
	$arr[]=$hash;
}
//debug($arr);
$this->set('results', $arr);
$this->set('_serialize','results');
//		$this->layout = 'js/js1';
		//$this->set('results',$r);
	}

}