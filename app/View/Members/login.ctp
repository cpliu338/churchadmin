<?php
	echo $this->Session->flash('auth'),
	$this->Form->create('User'),
	$this->Form->input('nickname'),
	$this->Form->input('pwd'),
	$this->Form->end('Login');
?>
