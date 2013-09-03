<?php

class Attendance extends AppModel {
    
/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Member' => array(
			'className' => 'Member',
			'foreignKey' => 'member_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
        
        public function toggle($id, $timestamp=null) {
            if ($timestamp == null) {
                $timestamp = date('Y-m-d H:i:s');
            }
            $ret['timestamp'] = $timestamp;
            $cond = array('Attendance.time1 LIKE' => substr($timestamp, 0, 10).'%',
                'Attendance.member_id'=>$id);
            $found = $this->find('list',array('conditions'=>$cond));
            if (empty($found)) {
                $rec = array();
                $rec['Attendance']=array();
                $rec['Attendance']['member_id'] = $id;
                $rec['Attendance']['time1'] = $timestamp; 
                $ret['result'] = $this->save($rec) ? 'added' : 'failure';
            }
            else {
                $ret['timestamp'] = '';
                $ret['result'] = $this->deleteAll(array('Attendance.id IN'=>array_keys($found))) ?
                        'deleted' : 'failure';
            }
            return $ret;
        }
}
?>
