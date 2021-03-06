<?php
App::uses('AppController', 'Controller','Number');
/**
 * Description of OffersController
 *
 * @author Administrator
 */
class OffersController extends AppController {

	var $uses = array('Offer', 'Entry', 'Account', 'Member');
	public $paginate = array(
            'fields' => array('Offer.id', 'Offer.date1','Offer.amount', 'Offer.receipt',
                'Member.name',
                'Account.name_chi'),
        'limit' => 10,
        );
    
    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('numberOptions',$this->numberOptions);
    }
    
    /**
    * populate first character of name, members (people), offer type accounts
    */
    private function populate() {
		$raw = $this->Offer->Member->find('all',array('group'=>'name1','order'=>'name1',
			'recursive'=>0,
			'conditions'=>array('Member.id <'=>10000),
			'fields'=>array('LEFT(Member.name,1) AS name1')
		));
		foreach ($raw as $entry) {
			$e = $entry[0]['name1'];
			$arr[$e]=$e;
		}
		$this->set('members', $this->Offer->Member->find('list', array('order'=>'Member.name','conditions'=>array('Member.id <'=>10000))));
		$this->set('accounts', $this->Offer->Account->find('list', array('order'=>'Account.id', 'fields'=>array('Account.id','Account.name_chi'),
			'conditions'=>['Account.code LIKE'=>'41%', 'Account.code NOT LIKE'=>'%0'])));
		$this->set('name1', $arr);
    }
    
    public function admin_edit($id) {
    	$this->edit($id);
    }
    
    public function edit($id) {
		$offer = $this->Offer->findById($id);
		if (empty($offer))
			throw new NotFoundException(__('Offer') . __('not found'));
    	//if ($this->request->is('get')) {
    	if (empty($this->data)) {
			$this->set('admin', !empty($this->request->params['admin']));
			$this->request->data=$offer;
			$this->populate();
			$this->render('edit');
		}
		else {
			if (empty($this->request->params['admin']) && $offer['Offer']['posted'])
				throw new ForbiddenException(__('Offer') . __('already posted'));
			if (!preg_match('/^20\d\d\-\d\d-\d\d$/', $this->data['Offer']['date1'])) {
				$this->Offer->validationErrors['date1'] = "Invalidate date";
				$this->populate();
				$this->render('edit');
				return;
			}
			if ($this->Offer->save($this->data)) {
				$this->Session->setFlash(__('Saved'));
				$this->redirect(array('controller'=>'offers','action'=>'view', 'admin'=>false, $this->data['Offer']['date1']));
			}
			else {
			$this->populate();
			$this->render('edit');
			}
		}
    }

    public function create() {
        if ($this->request->is('post') && $this->request->is('ajax')) {
        	$this->Offer->set($this->request->data);
        	//if ($this->Offer->validates())
        	if ($this->Offer->save($this->data))
				$ret = "Saved";//json_encode($this->data);
			else
				$ret = "Error";
        }
        else if ($this->request->is('post')) {
            $ret = "POST";
        }
        else {
            $ret = "None";
        }
        $this->set('text',$ret);
        $this->render('create', 'ajax');
    }
    
    public function add() {
		$this->loadModel('Member');
		$raw = $this->Offer->Member->find('all',array('group'=>'name1','order'=>'name1',
			//'fields'=>array('name1'),
			'recursive'=>0,
			'conditions'=>array('Member.id <'=>10000),
			'fields'=>array('LEFT(Member.name,1) AS name1')
		));
		foreach ($raw as $entry) {
			$e = $entry[0]['name1'];
			$arr[$e]=$e;
		}
		$this->set('name1', $arr);
		$this->set('lastSunday', $this->Offer->getLastSunday());
		$this->set('members', $this->Offer->Member->find('list', array('order'=>'Member.name','conditions'=>array('Member.id <'=>10000))));
		$this->set('accounts', $this->Offer->Account->find('list', array('order'=>'Account.id', 'fields'=>array('Account.id','Account.name_chi'),
			'conditions'=>['Account.code LIKE'=>'41%', 'Account.code NOT LIKE'=>'%0'])));
		if (empty($this->data)) {
//			if (!$d || !preg_match('/^20\d\d\-\d\d-\d\d$/', $d))
				$this->request->data('Offer.date1', $this->Offer->getLastSunday());
			// else
				// $this->data['Offer']['date1'] = $d;
			$this->request->data('Offer.amount', 0);
		}
		else {
			if ($this->Offer->save($this->data)) {
				$this->request->data('Offer.amount', 0);
				$this->Session->setFlash('The offer has been saved.');
			}
		}
    }
    
    public function index() {
        $this->set('offers',
            $this->Offer->find('all', array(
                'order'=>'Offer.date1 DESC',
                'fields'=>array('Offer.date1','SUM(Offer.amount) AS total'),
                'group'=>'Offer.date1',
                'limit'=>10
            ))
        );
    }
    
    /**
    */
    public function post($date1=NULL) {
        if (!$this->isLevelEnough(90)) {
            throw new ForbiddenException(__('Forbidden'));
        }
        if (!preg_match('/^20[0-9]{2}-[0-1]?[0-9]-[0-3]?[0-9]$/',$date1)) {
            throw new NotFoundException(__('Invalid')+" $date1");
        }
        $this->set('date1', $date1);
        if ($this->request->is('get')) {
			$this->set('offers',
				$this->Offer->find('all', array(
					'conditions'=>array('Offer.date1'=>$date1, 'Offer.posted'=>false),
					'order'=>'Account.code'
				))
			);
			$this->set('accounts', $this->Offer->Account->find('list', 
				array(
					'order'=>'Account.id', 
					'fields'=>array('Account.id','Account.name_chi'),
					'conditions'=>array('Account.code REGEXP'=>'^11.*[^0]$')
					// array('Account.id <'=>20000)
				)
			));
		}
		else {
			$offers = $this->Offer->find('all',
					array('conditions'=>array('Offer.date1'=>$date1, 'posted'=>false),
						'fields'=>array('SUM(amount) AS tot', 'account_id'),
						'group'=>'account_id',
						)
				);
			$this->Offer->updateAll(
				array('Offer.posted'=>true),
				array('Offer.date1'=>$date1/*, 'posted'=>false*/)
			);

			$nextTransId = $this->Entry->nextTransref();
			$ar = array();

			//$reportdate = $this->data['Offer']['date'];

			$tot = 0;
			foreach ($offers as $offer) {
				$ar[] = array(
					'transref'=>$nextTransId,
					'account_id'=>$offer['Offer']['account_id'],
					'date1'=>$date1,
					'amount'=>$offer[0]['tot'],
					'detail'=>"Offer on $date1"
					);
				$tot -= $offer[0]['tot'];

			}
			$ar[] = array(
					'transref'=>$nextTransId,
					'account_id'=>$this->data['Offer']['account_id'],
					'date1'=>$date1,
					'amount'=>$tot,
					'detail'=>"Offer on $date1"
					);

			if ($this->Entry->saveAll($ar)) {
				$this->flash('Entries have been inserted.',
				array('controller'=>"entries",
				'action'=>"add",$nextTransId));
			}
			else {
				debug($this->data);
			}
		}
    }

    public function view($date1=null) {
        if (!preg_match('/^20[0-9]{2}-[0-1]?[0-9]-[0-3]?[0-9]$/',$date1)) {
            throw new NotFoundException(__('Invalid')+" $date1");
        }
        $this->set('date1', $date1);
        $this->set('host',$_SERVER['SERVER_NAME']);
        $this->set('port',80);
        $offers = $this->Offer->find('all', array(
        	'fields'=>array('Offer.id','Offer.posted','Offer.amount','Offer.date1',
        		'Member.name',
        		'Account.id','Account.name_chi','Account.name'
			),
			'conditions'=>array('Offer.date1'=>$date1),
			'order'=>'Account.code'
		));
        $this->set('offers',$offers
        );
        $this->set('isAdmin', $this->isLevelEnough(90));
        if ($this->RequestHandler->isXml()) {
        	Configure::write('debug', 0);
        	//$this->set('_serialize','offers');
        }
    }    
    /**
     * List all offers by receipt batch
     * Prepare to mark for receipts
     */
    public function admin_index() {
        $receipt = array_key_exists('receipt', $this->request->query) ? $this->request->query['receipt'] : 0;
        $this->paginate['conditions'] = array('Offer.receipt' => $receipt, 'Offer.posted'=>true);
        $this->paginate['order'] = array('Offer.date1' => 'desc');
        $this->set('offers', $this->paginate());
        $this->set('base', $this->request->base);
        $this->set('option', $receipt);
        $this->set('maxbatch', 10);
    }
    
    /**
     * Update receipts (mark receipt batch number) for offer items with receipt = Offer[offerid].receipt
     * @param type $mode can be 1) date1, mark receipts on or above this date
     * 2) name, mark receipts for this name
     * @param type $offerid The offerid from which to extract param
     */
    public function admin_mark($mode, $offerid) {
            $ref = $this->Offer->findById($offerid);
            if ($mode == 'member') {
                $cond = array('Offer.receipt'=>$ref['Offer']['receipt'],
                    'Member.name'=>$ref['Member']['name']);
                $extra = 'from ' . $ref['Member']['name'];
            }
            else { // date
                $cond = array('Offer.receipt'=>$ref['Offer']['receipt'],
                    'Offer.date1 <='=>$ref['Offer']['date1']);
                $extra = 'earlier than or on ' . $ref['Offer']['date1'];
            }
            $count = $this->Offer->find("count", array('conditions'=>$cond));
            $oldbatch = $ref['Offer']['receipt'];
        if ($this->request->is('post')) {
            $newbatch=$this->data['Offer']['receipt'];
            $this->Offer->updateAll(
                array('Offer.receipt' => $newbatch),
                $cond);
            $this->flash("Marked $count offer items ($extra) from receipt batch $oldbatch to $newbatch", 
                array('action'=>'index','admin'=>true,
                    '?'=>array('receipt'=>$newbatch)    )
            );
        }
        //if ($this->request->is('get')) {
            $this->set('prompt', "Mark $count offer items ($extra) from receipt batch $oldbatch to: ");
        //}
    }
    
    public function fromMember($id) {
        $this->set('offers',
            $this->Offer->find('all', array(
                'order'=>'Offer.date1 DESC',
                'conditions'=>array('Member.id'=>$id)
                //,'limit'=>10
            ))
        );
    }

}

?>
