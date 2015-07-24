<?php
//App::uses('Shell', 'Console');

class CreateMembersShell extends AppShell {
	
	public $uses = array('Member');
	
	public function create_n($n) {
		$mem = $this->Member->find('first', [
			'conditions'=>['id <' =>800],
			'order'=>'id DESC'
		]);
        $id = $mem['Member']['id'];
        $data = array();
        for ($id1=$id; $id1<=$id+$n && $id1<800; $id1++) {
        	array_push($data,
				array('Member'=>array('id'=>$id1, 'nickname'=>"Member$id1", 'name'=>"Member$id1",'level'=>0, 'groupname'=>'special'))
			);
		}
		$this->Member->saveMany($data);
		$saved = $id1 - $id -1;
		$this->out("Created $saved members");
    }

	public function main() {
		$this->create_n($this->args[0]);
	}
	
}
?>