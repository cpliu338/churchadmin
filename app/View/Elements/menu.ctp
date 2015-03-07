<nav>
<?php
	echo $this->Html->link(__('Accounts'),array('controller'=>'accounts','action'=>'index','admin'=>false)),
	' | ',
	$this->Html->link(__('Entries'),array('controller'=>'entries','action'=>'index','admin'=>false)),
	' | ',
	$this->Html->link(__('Members'),array('controller'=>'members','action'=>'index','admin'=>false)),
	' | ',
	$this->Html->link(__('Offers'),array('controller'=>'offers','action'=>'index','admin'=>false)),
	' | ',
	$this->Html->link(__('Payable'),array('controller'=>'entries','action'=>'pay','admin'=>false)),
	' | ',
	$this->Html->link(__('Attendance'),array('controller'=>'attendances','action'=>'stat','admin'=>false));
	if ($toggle) {
		echo' | ',
		$this->Html->link(__('Toggle admin'),array('action'=>'index','admin'=>!$this->request->admin));
	}
	echo ' | ',
	$this->Html->link(__('Logout'),array('controller'=>'members','action'=>'logout','admin'=>false));
?>
</nav>
