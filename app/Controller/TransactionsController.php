<?php
App::uses('AppController', 'Controller');
/**
 * Transactions Controller
 *
 * @property Transaction $Transaction
 */
class TransactionsController extends AppController {
	
	public $helpers = array('Js' => array('Jquery'));
	
	public $paginate = array(
			'fields' => array('Transaction.id', 'Transaction.tran_id','Transaction.amount','Transaction.detail',
				'Account.name','Account.name_chi'),
        'limit' => 2,
        'order' => array(
            'Transaction.date1' => 'asc','Transaction.tran_id' => 'asc'
        )
    );

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$st_date = '';
		if ($this->request->is('post')) {
			$st_date = $this->data['Transaction']['date1'];
			$this->Transaction->setStartDate($st_date);
			//debug($this->data['Transaction']['date1']);
		}
		else {
			$st_date = $this->Transaction->getStartDate();
		}
		$this->request->data['Transaction']['date1'] = $st_date;
		$end_date = $this->Transaction->getEndDate($st_date);
		$this->set('end_date',$end_date);
		$this->paginate = array(
			'fields' => array('Transaction.id', 'Transaction.tran_id','Transaction.amount','Transaction.detail',
				'Transaction.date1', 'Account.name','Account.name_chi'),
				'conditions' => array(
					'Transaction.date1 >=' => $st_date,
					'Transaction.date1 <=' => $end_date
					),
				'limit' => 25,
			'order' => array(
				'Transaction.date1' => 'asc','Transaction.tran_id' => 'asc')
			);
		//$this->Transaction->recursive = 0;
		$data = $this->paginate('Transaction');
		$this->set('transactions', $data);
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Transaction->exists($id)) {
			throw new NotFoundException(__('Invalid transaction'));
		}
		$options = array('conditions' => array('Transaction.' . $this->Transaction->primaryKey => $id));
		$this->set('transaction', $this->Transaction->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Transaction->create();
			if ($this->Transaction->save($this->request->data)) {
				$this->Session->setFlash(__('The transaction has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The transaction could not be saved. Please, try again.'));
			}
		}
		$accounts = $this->Transaction->Account->find('list');
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
		if (!$this->Transaction->exists($id)) {
			throw new NotFoundException(__('Invalid transaction'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Transaction->save($this->request->data)) {
				$this->Session->setFlash(__('The transaction has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The transaction could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Transaction.' . $this->Transaction->primaryKey => $id));
			$this->request->data = $this->Transaction->find('first', $options);
		}
		$accounts = $this->Transaction->Account->find('list');
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
		$this->Transaction->id = $id;
		if (!$this->Transaction->exists()) {
			throw new NotFoundException(__('Invalid transaction'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Transaction->delete()) {
			$this->Session->setFlash(__('Transaction deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Transaction was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
	
	public function admin_index() {
		$st_date = '';
		$detail = '';
		if ($this->request->is('ajax')) {
			$st_date = $this->Transaction->getStartDate();
			$end_date = $this->Transaction->getEndDate($st_date);
			$detail = $this->Transaction->getDetail();
		}
		else if ($this->request->is('post')) {
			$st_date = $this->data['Transaction']['date1'];
			$this->Transaction->setStartDate($st_date);
			$detail = trim($this->data['Transaction']['detail']);
			$this->Transaction->setDetail($detail);
			$end_date = $this->Transaction->getEndDate($st_date);
			$this->set('end_date',$end_date);
		}
		else { // HTTP GET form
			$st_date = $this->Transaction->getStartDate();
			$end_date = $this->Transaction->getEndDate($st_date);
			$detail = $this->Transaction->getDetail();
			$this->set('end_date',$end_date);
			$this->request->data['Transaction']['date1'] = $st_date;
			$this->request->data['Transaction']['detail'] = $this->Transaction->getDetail();
		}
		$this->paginate = array(
			'fields' => array('Transaction.id', 'Transaction.tran_id','Transaction.amount','Transaction.detail',
				'Transaction.date1', 'Account.name','Account.name_chi'),
				'conditions' => array(
					'Transaction.date1 >=' => $st_date,
					'Transaction.date1 <=' => $end_date,
					'Transaction.detail LIKE' => "%$detail%"
					),
				'limit' => 25,
			'order' => array(
				'Transaction.date1' => 'asc','Transaction.tran_id' => 'asc')
			);
		$data = $this->paginate('Transaction');
		$this->set('transactions', $data);
		if ($this->request->is('ajax')) {
			$this->render('admin_edit','ajax');
		}
	}
	
}

