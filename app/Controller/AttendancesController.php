<?php
App::uses('AppController', 'Controller');
/**
 * Description of AttendancesController
 *
 * @author Administrator
 */
class AttendancesController extends AppController {
    public $helpers = array('Form', 'Html', 'Js');
    public $components = array('RequestHandler');
    var $uses = array('Attendance','Member');
    
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->Allow('index', 'toggle');
    }
    
    public function index() {
        $this->set('members', $this->Member->find('all',
            array(
                'limit'=>10
            )
        ));
    }
    
    public function toggle($id) {
        $this->layout='ajax';
        $total = 234;
        if ($this->request->is('ajax')) {
            Configure::write('debug', 0);
            $total = $id;
            $this->set('amt', $total);        
            $this->render('/Elements/ajax_amount');
        }
    }
}
?>
