<?php
App::uses('AppController', 'Controller','Number');
/**
 * Description of OffersController
 *
 * @author Administrator
 */
class OffersController extends AppController {
    
    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('numberOptions',$this->numberOptions);
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
/**/
		$this->set('members', $this->Offer->Member->find('list', array('order'=>'Member.name','conditions'=>array('Member.id <'=>10000))));
/**/
		$this->set('accounts', $this->Offer->Account->find('list', array('order'=>'Account.id', 'fields'=>array('Account.id','Account.name_chi'),
			'conditions'=>array('Account.code REGEXP'=>'^41[1-9]+$'))));
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

    public function view($date1=NULL) {
        if (!preg_match('/^20[0-9]{2}-[0-1]?[0-9]-[0-3]?[0-9]$/',$date1)) {
            throw new NotFoundException(__('Invalid')+" $date1");
        }
        $this->set('date1', $date1);
        $this->set('offers',
            $this->Offer->find('all', array(
                'conditions'=>array('Offer.date1'=>$date1),
                'order'=>'Account.code'
            ))
        );
    }    
    /**
     * List all offers
     * Todo: set order, pagination options etc
     */
    public function admin_index() {
            $this->Offer->recursive = 0;
            $this->set('offer', $this->paginate());
    }

}

?>
