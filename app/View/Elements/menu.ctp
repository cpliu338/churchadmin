<nav>
<?php
	echo $this->Html->link(__('Accounts'),array('controller'=>'accounts','action'=>'index','admin'=>false)),
	' | ',
	$this->Html->link(__('Entries'),array('controller'=>'entries','action'=>'index','admin'=>false))
?>
</nav>