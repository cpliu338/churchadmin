<?php 
	echo $this->element('menu', array('toggle'=>$toggle));
	echo $account['Account']['code'], $account['Account']['name_chi'];
//	echo $this->Paginator->numbers();
	echo $this->element('entries1', array('transactions'=>$transactions));
?>
