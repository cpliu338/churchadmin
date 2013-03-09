<nav>
<?php
	echo $this->Html->link(__('Accounts'),array('controller'=>'accounts','action'=>'index','admin'=>false)),
	' | ',
	$this->Html->link(__('Entries'),array('controller'=>'transactions','action'=>'index','admin'=>false)),
	' | ',
	$this->Html->link(__('Logout'),array('controller'=>'members','action'=>'logout','admin'=>false));
?>
</nav>
