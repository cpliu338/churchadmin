<?php
App::uses('AppController', 'Controller');
App::uses('Number','Helper');
App::uses('Js','Helper');
/**
 * Description of EntriesController
 *
 * @author Administrator
 */
class EntriesController  extends AppController {
	public $helpers = array('Number','Js' => array('Jquery'));
	
	public $paginate = array(
			'fields' => array('Entry.id', 'Entry.transref','Entry.amount','Entry.detail',
				'Account.name','Account.name_chi'),
        'limit' => 2,
        'order' => array(
            'Entry.date1' => 'asc','Entry.transref' => 'asc'
        )
    );
    
    	public $ac_types=array(
		'1'=>'資產',
		'2'=>'負債',
		//'3'=>'產權',
		'4'=>'收入',
		'51'=>'薪津',
		'52'=>'各項服務',
		'53'=>'辦公室',
		'54'=>'專業服務',
		'55'=>'支持關聯機構',
		'56'=>'差傳開支');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('numberOptions', $this->numberOptions);
        $this->Auth->allowedActions=array('setNextCheque','download');
    }

	/**
	* Set next cheque no using json (restful) 
	* To set it to e.g. 1234, POST application/json with {"cheqno":1234}
	*/    
    public function setNextCheque() {
    	if ($this->request->isPost()) {
    		$nextChequeNo = $this->data['cheqno'];
    		Cache::write('cheqno',$nextChequeNo);
    	}
    	else {
    		$nextChequeNo = Cache::read('cheqno');
    	}
		$nextCheque = array('cheqno'=>$nextChequeNo);
		$this->set('nextCheque', $nextCheque);
		$this->set('_serialize','nextCheque');
    }
    
    function totalize($code) {
        $this->layout='ajax';
        if ($this->request->is('ajax'))
            Configure::write('debug', 0);
        $code2 = $code;
        if (substr($code, -1)=='0') {
            $code2[strlen($code)-1]='%';
        }
        $subcodes = array();
        foreach ($this->Entry->Account->find('list', array(
            'conditions'=>array('Account.code LIKE'=>$code2)
            )) as $id=>$name) {
                array_push($subcodes, $id);
        }
        $conditions = array(
            'Entry.date1 >='=>$this->Entry->getYearStart($this->Entry->getStartDate()),
            'Entry.date1 <='=>$this->Entry->getYearEnd($this->Entry->getStartDate())
        );
        switch (count($subcodes)) {
            case 0: $conditions['Entry.account_id']=FALSE; break;
            case 1: $conditions['Entry.account_id']=$subcodes[0]; break;
            default: $conditions['Entry.account_id IN']=$subcodes;
        }
        $result = $this->Entry->find("first",array(
            'fields'=>'SUM(Entry.amount) AS sum',
            'conditions'=>$conditions
        ));
        //debug($conditions);
        $total = (empty($result[0]['sum'])) ? "No record" : $result[0]['sum'];
        $this->set('amt', $total);
//        $this->set('_serialize','total');
        $this->render('/Elements/ajax_amount');
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
			$this->redirect(array('action'=>'index'));
		}
		else {
			$st_date = $this->Entry->getStartDate();
		}
		$this->request->data['Entry']['date1'] = $st_date;
		$end_date = $this->Entry->getEndDate($st_date);
		$this->set('end_date',$end_date);
		$this->paginate = array(
			'fields' => array('Entry.id', 'Entry.transref','Entry.amount','Entry.detail','Entry.extra1',
				'Entry.date1', 'Account.name','Account.name_chi','Account.id'),
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
 * vet method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
    public function vet($id = null) {
        if (!$this->Entry->Account->exists($id)) {
                throw new NotFoundException(__('Invalid account'));
        }
        $signed = $this->Entry->find('all',
            array('conditions' => array('Entry.account_id' => $id,
                    'Entry.extra1 LIKE'=>'$%'),
                'order'=>'Entry.extra1')
                    );
        $this->set('entries', $signed);
        /*
            array('conditions' => array('Entry.account_id' => $id,
                    'Entry.extra1 LIKE'=>'$%'),
                'order'=>'Entry.extra1')
                    )); */
        $tot = 0;
        foreach ($signed as $a) {
        	$tot += $a['Entry']['amount'];
        }
        $this->set('total', $tot);
        if ($this->request->is('post')) {
            if (!empty($this->data['EntryId']))
                $cnt = count($this->data['EntryId']);
            else
                $cnt = 0;
            switch ($cnt) {
                case 0: $cond = null; break;
                case 1: $cond = array('Entry.id' => $this->data['EntryId'][0]); break;
                default : $cond = array('Entry.id IN' => $this->data['EntryId']); break;
            }
            if (!empty($cond))
                $this->Entry->updateAll(
                    array('Entry.extra1' => "CONCAT('#',MID(extra1,2,10))"),
                    $cond
            );
            $this->Session->setFlash(__("Updated %d entries", $cnt));
            $this->redirect(array('action'=>'vet',$id));
        }
    }

    public function create() {
        $accounts = $this->Entry->Account->find('list',
            array('conditions'=>array('Account.code LIKE'=>'11%', 'NOT'=>array('Account.code LIKE'=>'%0')),
                'fields' => array('Account.id', 'Account.name_chi'))
        );
        $this->set(compact('accounts'));
        $cheqno = Cache::read('cheqno');
        $this->set('cheqno',$cheqno);
        $this->set('extra1',__('ExtraInfo'));
        $this->set('cheque',__('ChequeNumber'));
        if ($this->request->is('post')) {
            $checking = $this->Entry->Account->isChecking($this->data['Entry']['account_id']);
            $this->set('isChecking',$checking);
            if ($checking) {
                // pre-processing for checking accounts
                if (!preg_match('/^[0-9]{1,6}$/', $this->data['Entry']['extra1'])) {
                    $this->Entry->validationErrors['extra1']='invalid cheque number';
                    return;
                }
                if (!preg_match('/^[0-9]+(\.[0-9][0-9]?)?$/', $this->data['Entry']['amount'])) {
                    $this->Entry->validationErrors['amount']='invalid amount ###.##';
                    return;
                }
                Cache::write('cheqno',$this->data['Entry']['extra1']+1);
                $this->request->data('Entry.extra1','$'.$this->data['Entry']['extra1']);
//                if ($this->Entry->save($this->request->data)) {
//                    $this->Session->setFlash($this->Entry->getLastInsertID());
//                    $this->redirect(array('action'=>'index'));
//                    return;
//                }
            }
            else {
                // extra1 not used
                $this->request->data('Entry.extra1','');
            }
            $this->request->data('Entry.transref',$this->Entry->nextTransref());
            $this->Entry->setCreateDate($this->data['Entry']['date1']);
            if ($this->Entry->save($this->request->data)) {
                $this->Session->setFlash(__('Saved'));
                $this->redirect(array('action'=>'edit', $this->Entry->getLastInsertID()));
            }
        }
        else { //HTTP GET
            $this->set('isChecking',TRUE);
            $this->request->data('Entry.date1',$this->Entry->getCreateDate()); 
            $this->request->data('Entry.account_id',11201);
            $this->request->data('Entry.extra1',$cheqno);
//            debug($this->Entry->nextTransref());
        }
    }
/**
 * add method
 *
 * @return void
 */
	public function add($transref) {
		$entries = $this->Entry->find('all',array('conditions'=>array(
			'Entry.transref'=>$transref)
			));
        if (empty($entries)) {
                throw new NotFoundException(__('Invalid entry reference'));
        }
        $this->set('options', $this->ac_types);
		$this->set('entries', $entries);
		$accounts = $this->Entry->Account->find('list', 
			array(
				'order'=>'Account.id', 
				'fields'=>array('Account.id','Account.name_chi')
			)
		);
		$this->set(compact('accounts'));
		if ($this->request->is('post')) {
			// $this->Entry->create();
			$entry = $entries[0];
			$this->request->data('Entry.id',null);
			$this->request->data('Entry.extra1','');
			$this->request->data('Entry.date1',$entry['Entry']['date1']);
			$this->request->data('Entry.transref',$entry['Entry']['transref']);
			if ($this->Entry->save($this->request->data)) {
				$this->Session->setFlash(__('The entry has been saved'));
				$this->redirect(array('action' => 'edit',$this->Entry->id));
			} else {
				$this->Session->setFlash(__('The entry could not be saved. Please, try again.'));
				$this->render('edit');
			}
		}
		else {
			$this->request->data('Entry.id',null);
			$this->request->data('Entry.amount',0.0);
			$this->render('edit');
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
        if (!$this->Entry->exists($id)) {
                throw new NotFoundException(__('Invalid entry'));
        }
        $this->set('options', $this->ac_types);
        if ($this->request->is('post') || $this->request->is('put')) {
            $entry = $this->Entry->find('first', array('conditions' => array('Entry.id' => $id)));
            $entry['Entry']['detail']=$this->data['Entry']['detail'];
            $entry['Entry']['account_id']=$this->data['Entry']['account_id'];
            $entry['Entry']['amount']=$this->data['Entry']['amount'];
            $entry['Entry']['extra1']=$this->data['Entry']['extra1'];
                    if ($this->Entry->save($entry)) {
                            $this->Session->setFlash(__('The entry has been saved'));
                            //$this->redirect(array('action' => 'index'));
                    } else {
                            $this->Session->setFlash(__('The entry could not be saved. Please, try again.'));
                    }
        } 
			$options = array('conditions' => array('Entry.' . $this->Entry->primaryKey => $id));
                $this->request->data = $this->Entry->find('first', $options);
                $this->set("entries", $this->Entry->find('all', array(
                    'conditions'=>array('Entry.transref'=>$this->data['Entry']['transref'])
                )));
                /*
            $accounts = $this->Entry->Account->find('list', 
				array(
					'order'=>'Account.id', 
					'fields'=>array('Account.id','Account.name_chi')
				)
			);
            $this->set(compact('accounts'));
            */
    }

    function pay() {
        if ($this->request->is('post') || $this->request->is('put')) {
			$entry = $this->Entry->findById($this->data['Entry']['choice']);
			$date2 = $this->data['Entry']['date1'];
			if ($entry) {
				$entry['Entry']['detail'] = $this->data['Entry']['detail'];
				$entry['Entry']['extra1'] = '$'.$this->data['Entry']['extra1'];
				$entry['Entry']['account_id'] = '11201';
				unset($entry['Account']);
// NOTE updateAll does not detect field type, so need to escape for date1
				if ($this->Entry->save($entry) &&
					$this->Entry->updateAll(
						array('Entry.date1'=>"'$date2'"),
						array('Entry.transref'=>$entry['Entry']['transref'])
					)) {
					$this->Session->setFlash("Saved entry");
					return $this->redirect(array('action' => 'edit', $this->data['Entry']['choice']));
				}
				$this->Session->setFlash("Failed to save entry");
				debug ($entry);
			}
			else {
				$choice = $this->data['Entry']['choice'];
				$this->Session->setFlash("Invalid entry: $choice");
			}
        }
	    $date1 =$this->Entry->getYearStart($this->Entry->getStartDate());
	    $date2 = $this->Entry->getYearEnd($date1);
	    $options = array('conditions' => array(
			'Entry.date1 >=' => $date1,
			'Entry.date1 <=' => $date2,
			'Entry.account_id LIKE' => '2_002',
			'Entry.amount >'=>0
		));

		$e2 = $this->Entry->find('all', $options);
		$es = array();
//		if (empty($e2))
			foreach ($e2 as $item) {
				$ex = $item['Entry'];
				$es[$ex['id']] = sprintf("%s ($%.2f) on %s", $ex['detail'], $ex['amount'], $ex['date1']); 
			}
		$this->set('entries', $es);
		$this->request->data = $e2[0]; 
		//$this->Entry->find('first', $options);
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
			throw new NotFoundException(__('Invalid entry'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Entry->delete()) {
			$this->Session->setFlash(__('Entry deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Entry was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
	
	/**
	If Restful, /choffice/admin/entries?upto=2013-05-31 fetches entries between Jan 1 to May 31
	*/
	public function admin_index() {
		if ($this->RequestHandler->isXml()) {
			//debug($this->request->query['upto']);
			$entries = $this->Entry->find('all', array(
				'conditions'=>array('Entry.date1 <='=>$this->request->query['upto'],
					'Entry.date1 >='=>$this->Entry->getYearStart($this->request->query['upto'])
				),
			));
			$this->set('entries',$entries);
			//$this->set('_serialize','entries');
		}
		else {
			$this->set('entries', $this->Entry->find('all', array(
				'fields'=>array("'Unbalanced' AS name",'Entry.id','Entry.date1','Entry.transref','SUM(Entry.amount) AS suma'),
				'group'=>'Entry.transref HAVING suma<>0.0'
			)));
			$date1 = $this->Entry->getStartDate();
			$this->set('entries1', $this->Entry->find('all', array(
				'fields'=>array("'Income as DB : ' AS name",'Entry.id','Entry.date1','Entry.transref','Entry.amount','Entry.detail'),
				'conditions' => array(
					'Entry.date1 >=' => $this->Entry->getYearStart($date1),
					'Entry.date1 <=' => $this->Entry->getYearEnd($date1),
					'Account.code LIKE' => '4%',
					'Entry.amount <'=>0
					),
			)));
			$this->set('entries2', $this->Entry->find('all', array(
				'fields'=>array("'Expenditure as CR : ' AS name",'Entry.id','Entry.date1','Entry.transref','Entry.amount','Entry.detail'),
				'conditions' => array(
					'Entry.date1 >=' => $this->Entry->getYearStart($date1),
					'Entry.date1 <=' => $this->Entry->getYearEnd($date1),
					'Account.code LIKE' => '5%',
					'Entry.amount >'=>0
					),
			)));
		}
	}
    
	public function admin_edit($id = null) {
        if (!$this->Entry->exists($id)) {
                throw new NotFoundException(__('Invalid entry'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
			$data = $this->Entry->findById($id);
			if (!preg_match('/^20\d\d\-\d\d-\d\d$/', $this->data['Entry']['date1'])) {
				$this->Entry->validationErrors['date1'] = "Invalidate date";
				return;
			}
			$data['Entry']['extra1'] = $this->data['Entry']['extra1'];
			$this->Entry->save($data);
			if ($data['Entry']['date1'] != $this->data['Entry']['date1']) {
				$date2=$this->data['Entry']['date1'];        	
				$this->Entry->updateAll(
					array('Entry.date1'=>"'$date2'"),
					array('Entry.transref'=>$data['Entry']['transref'])
				);
			}
			$this->set("entry", $data); 
			$this->set("entries", $this->Entry->find('all', array(
                    'conditions'=>array('Entry.transref'=>$data['Entry']['transref'])
                )));
			$this->Session->setFlash(__('Saved'));
        }
        else {
        	$this->edit($id);
        }
	}
	
	public function download($endDate) {
		if (!preg_match('/^(20\d\d)-\d{1,2}-\d{1,2}$/', $endDate, $matches))
			throw new NotFoundException();
		$this->set("entries", $this->Entry->find('all', array(
			'fields' => array('Entry.amount', 'Entry.account_id', 'Entry.date1'),
			'conditions'=>array('Entry.date1 <='=>$endDate, 
				'Entry.date1 >='=>$this->Entry->getYearStart($endDate)),//'2013-01-01'),
			'order'=>array('Entry.date1')
                )));
        $this->set('_serialize',array('entries'));
	}
	
	/**
		Renamed on Jun 29 2013, delete if found no use after 3 months
	*/
	public function admin_index2() {
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