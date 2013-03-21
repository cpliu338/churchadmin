<nav>
<?php
	echo $this->Html->link(__('Accounts'),array('controller'=>'accounts','action'=>'index','admin'=>false)),
	' | ',
	$this->Html->link(__('Entries'),array('controller'=>'transactions','action'=>'index','admin'=>false)),
	' | ',
	$this->Html->link(__('Members'),array('controller'=>'members','action'=>'index','admin'=>false)),
	' | ',
	$this->Html->link(__('Offers'),array('controller'=>'offers','action'=>'index','admin'=>false));
	if ($toggle) {
		echo' | ',
		$this->Html->link(__('Toggle admin'),array('action'=>'index','admin'=>!$this->request->admin));
	}
	echo ' | ',
	$this->Html->link(__('Logout'),array('controller'=>'members','action'=>'logout','admin'=>false));
?>
</nav>
