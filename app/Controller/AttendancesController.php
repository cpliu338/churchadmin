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
        if (substr($this->request->clientIp(TRUE),0,4)==='192.' || substr($this->request->clientIp(TRUE),0,4)==='127.')
//        debug($this->Auth->user());
            $this->Auth->Allow('index', 'toggle', 'barcode');
    }
    
    public function index($type=0) {
    	$members = $this->Member->find('all',
            array(
                'limit'=>8
            )
        );
        $today = date('Y-m-d');
        $groups = Configure::read('exclude.groups');
        $field = $type==0 ? 'groupname' : 'LEFT(name,1)';
        $records = $this->Attendance->query("select member2.id,member2.name,member2.grp,t2.time1 from (select id,name, $field AS grp from members " . 
        	"where groupname NOT IN $groups) as member2 ".
        	"left join (select member_id,time1 from attendances where attendances.time1 like '$today%') as t2 ".
        	"on member2.id = t2.member_id order by member2.grp");
        $this->set('members', $records);
        $this->set('type', $type);
        $this->set('base',$this->request->base);
        $this->set('total', $this->Attendance->find('count',array('conditions'=>array("Attendance.time1 LIKE"=>"$today%"))));
//        debug($this->request);
    }
    
    public function toggle() {
        if ($this->request->is('ajax')) {
        	$v2=$this->request->input('json_decode', true);
        	$id = substr($v2['Id'],3);
        	$name = $v2['Value'];
        	$this->autoRender = false;
        	$this->response->type('json');
            Configure::write('debug', 0);
            $ret = $this->Attendance->toggle($id);
            if ($ret['result']=='added') {
                $ret['oldClass']='c1';
                $ret['newClass']='c2';
                $ret['result']="已加入$name";
            }
            else if ($ret['result']=='deleted') {
                $ret['oldClass']='c2';
                $ret['newClass']='c1';
                $ret['result']="已刪除$name";
            }
            $ret['imgid']= "#img".substr($v2['Id'],3);
            $ret['msgid']= "#msg".substr($v2['Id'],3);
            $today = date('Y-m-d');
            $ret['total']= $this->Attendance->find('count',array('conditions'=>array("Attendance.time1 LIKE"=>"$today%")));
            $json = json_encode($ret);
            $this->response->body($json);
        }
        else { // for debug use only
            $cond = array('Attendance.time1 LIKE' => substr('2013-09-03 13:00:00', 0, 10).'%',
                'Attendance.member_id'=>34);
            $found = $this->Attendance->find('list',array('conditions'=>$cond));
            $this->Attendance->deleteAll(array('Attendance.id'=>array_keys($found)));
            $this->render('index');
        }
    }
    
    
    public function barcode($code='') {
    	$this->response->header('Refresh', '60; URL=' . 'http://'.$_SERVER['SERVER_NAME'].$this->request->here);
			$today = date('Y-m-d');
    		$this->set('today',$today);
		if ($this->request->is('ajax')) {
            Configure::write('debug', 0);
        	$v2=$this->request->input('json_decode', true);
        	$this->autoRender = false;
        	$member_id=($v2['Id']-10000000)/107;
        	$this->response->type('json');
        	$rec = $this->Attendance->find('first',array('conditions'=>array('Member.id'=>$member_id,
        		'Attendance.time1 LIKE'=>"$today%")
        		));
        	if (empty($rec)) {
				$mem = $this->Attendance->Member->find('first',array('conditions'=>
					array('Member.id'=>$member_id)
        		));
				if (empty($mem)) {
					$ret['text']= 'Invalid';
				}
				else {
					$rec = array('Attendance'=>array('member_id'=>$member_id));
					$this->Attendance->save($rec);
					$ret['text']= $mem['Member']['name'];
				}
        	}
        	else {
        		$ret['text']='Duplicated';
        	}
            $json = json_encode($ret);
            $this->response->body($json);
    	}
    	else {
			if ($this->request->is('post')) {
				$found = $this->Attendance->find('first',array('conditions'=>array('Member.id'=>$this->data['memberid'],
					'Attendance.time1 LIKE'=>"$today%")));
				if (!empty($found)) {
    		    	$this->Session->setFlash("Removed ".$found['Member']['name']);
    		    	$this->Attendance->delete($found['Attendance']['id']);
    		    }
    		}
    		// GET or POST will run the following
    			$this->set('cnt',$this->Attendance->find('count',array('conditions'=>array('Attendance.time1 LIKE'=>"$today%")))
    				);
				$records = $this->Attendance->find('all',array('conditions'=>array('Attendance.time1 LIKE'=>"$today%")));
				$this->set('attendances', $records);
				$this->set('base',$this->request->base);
		}
    }
}
?>
