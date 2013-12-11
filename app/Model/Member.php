<?php
App::uses('AppModel', 'Model');
/**
 * Member Model
 *
 */
class Member extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';
	public $validate = array('name' => 'isUnique');
	
	public function beforeSave() {
		if (isset($this->data['Member']['pwd'])) {
			$this->data['Member']['pwd'] = Security::hash($this->data['Member']['pwd'], 'md5', false);
		}
	}

}
