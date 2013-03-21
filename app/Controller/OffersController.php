<?php
App::uses('AppController', 'Controller','Number');
/**
 * Description of OffersController
 *
 * @author Administrator
 */
class OffersController extends AppController {
    
    var $numberOptions = array(
        'places' => 2,
        'escape' => false,
        'before' => '',
        'decimals' => '.',
        'thousands' => ','
    );

    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('numberOptions',$this->numberOptions);
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
