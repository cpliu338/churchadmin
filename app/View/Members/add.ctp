<?php 	echo $this->element('menu', array('toggle'=>$toggle)); ?>
<?php
	//debug($groups);
	echo $this->Form->Create('Member'),
	$this->Form->input('Member.name'),
	'Group',
	$this->Form->select('Member.groupname', $groups),
	$this->Form->end('add');
?>
