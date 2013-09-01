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
    	$members = $this->Member->find('all',
            array(
                'limit'=>8
            )
        );
        $today = date('Y-m-d');
        $records = $this->Attendance->query("select member2.id,member2.name,member2.groupname,t2.time1 from (select id,name,groupname from members where groupname in ('Telepathy','茶傾','Bridge')) as member2 left join (select member_id,time1 from attendances where attendances.time1 like '$today%') as t2 on member2.id = t2.member_id order by member2.groupname limit 10");
        $this->set('members', $records);
        //debug($records);
    }
    
    public function toggle() {
        if ($this->request->is('ajax')) {
        	$v2=$this->request->input('json_decode', true);
        	$id = substr($v2['Id'],3);
        	$this->autoRender = false;
        	$this->response->type('json');
            Configure::write('debug', 0);
            //$v = $v2['Id'];
            $rec = array();
            $rec['Attendance']=array();
            $rec['Attendance']['member_id'] = $id;
            $t = date('Y-m-d H:i:s');
            $rec['Attendance']['time1'] = $t; 
            if ($this->Attendance->save($rec)) {
            	$result = "Added $t";
            }
            else {
            	$result = "Failed";
            }
            $json = json_encode(array('note'=>$result,'imgid'=>"#img".substr($v2['Id'],3)));
            $this->response->body($json);
        }
    }
}
?>
