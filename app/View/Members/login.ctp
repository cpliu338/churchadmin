<?php
	echo $this->Session->flash('auth'),
	$this->Form->create('Member'),
	$this->Form->input('nickname'),
	$this->Form->input('pwd', array('type' => 'password')),
	$this->Form->end('Login');
?>
