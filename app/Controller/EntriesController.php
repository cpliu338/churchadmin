<?php
App::uses('AppController', 'Controller','Number');
/**
 * Description of EntriesController
 *
 * @author Administrator
 */
class EntriesController  extends AppController {
	public $helpers = array('Js' => array('Jquery'));
	
	public $paginate = array(
			'fields' => array('Entry.id', 'Entry.transref','Entry.amount','Entry.detail',
				'Account.name','Account.name_chi'),
        'limit' => 2,
        'order' => array(
            'Entry.date1' => 'asc','Entry.transref' => 'asc'
        )
    );
        
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
		$st_date = '';
		if ($this->request->is('post')) {
			$st_date = $this->data['Entry']['date1'];
			$this->Entry->setStartDate($st_date);
			//debug($this->data['Entry']['date1']);
		}
		else {
			$st_date = $this->Entry->getStartDate();
		}
		$this->request->data['Entry']['date1'] = $st_date;
		$end_date = $this->Entry->getEndDate($st_date);
		$this->set('end_date',$end_date);
		$this->paginate = array(
			'fields' => array('Entry.id', 'Entry.transref','Entry.amount','Entry.detail','Entry.extra1',
				'Entry.date1', 'Account.name','Account.name_chi'),
				'conditions' => array(
					'Entry.date1 >=' => $st_date,
					'Entry.date1 <=' => $end_date
					),
				'limit' => 25,
			'order' => array(
				'Entry.date1' => 'asc','Entry.transref' => 'asc')
			);
		//$this->Entry->recursive = 0;
		$data = $this->paginate('Entry');
		$this->set('entries', $data);
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Entry->exists($id)) {
			throw new NotFoundException(__('Invalid transaction'));
		}
		$options = array('conditions' => array('Entry.' . $this->Entry->primaryKey => $id));
		$this->set('transaction', $this->Entry->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Entry->create();
			if ($this->Entry->save($this->request->data)) {
				$this->Session->setFlash(__('The transaction has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The transaction could not be saved. Please, try again.'));
			}
		}
		$accounts = $this->Entry->Account->find('list');
		$this->set(compact('accounts'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Entry->exists($id)) {
			throw new NotFoundException(__('Invalid transaction'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Entry->save($this->request->data)) {
				$this->Session->setFlash(__('The transaction has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The transaction could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Entry.' . $this->Entry->primaryKey => $id));
			$this->request->data = $this->Entry->find('first', $options);
		}
		$accounts = $this->Entry->Account->find('list');
		$this->set(compact('accounts'));
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
		$this->Entry->id = $id;
		if (!$this->Entry->exists()) {
			throw new NotFoundException(__('Invalid transaction'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Entry->delete()) {
			$this->Session->setFlash(__('Entry deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Entry was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
	
	public function admin_index() {
		$st_date = '';
		$detail = '';
		if ($this->request->is('ajax')) {
			$st_date = $this->Entry->getStartDate();
			$end_date = $this->Entry->getEndDate($st_date);
			$detail = $this->Entry->getDetail();
		}
		else if ($this->request->is('post')) {
			$st_date = $this->data['Entry']['date1'];
			$this->Entry->setStartDate($st_date);
			$detail = trim($this->data['Entry']['detail']);
			$this->Entry->setDetail($detail);
			$end_date = $this->Entry->getEndDate($st_date);
			$this->set('end_date',$end_date);
		}
		else { // HTTP GET form
			$st_date = $this->Entry->getStartDate();
			$end_date = $this->Entry->getEndDate($st_date);
			$detail = $this->Entry->getDetail();
			$this->set('end_date',$end_date);
			$this->request->data['Entry']['date1'] = $st_date;
			$this->request->data['Entry']['detail'] = $this->Entry->getDetail();
		}
		$this->paginate = array(
			'fields' => array('Entry.id', 'Entry.transref','Entry.amount','Entry.detail',
				'Entry.date1', 'Account.name','Account.name_chi'),
				'conditions' => array(
					'Entry.date1 >=' => $st_date,
					'Entry.date1 <=' => $end_date,
					'Entry.detail LIKE' => "%$detail%"
					),
				'limit' => 25,
			'order' => array(
				'Entry.date1' => 'asc','Entry.transref' => 'asc')
			);
		$data = $this->paginate('Entry');
		$this->set('entries', $data);
		if ($this->request->is('ajax')) {
			$this->render('admin_edit','ajax');
		}
	}
	
}
