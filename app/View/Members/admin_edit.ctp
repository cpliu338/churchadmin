<?php
	echo 
	$this->Form->create('Member'),
	$this->Form->input('nickname'),
	$this->Form->input('level'),
	$this->Form->end('Update');
?>
