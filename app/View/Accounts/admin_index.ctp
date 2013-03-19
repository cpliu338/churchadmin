<?php 
	echo $this->element('menu', array('toggle'=>$toggle));
?>
<table>
<?php
	echo $this->Html->tableHeaders(array(__('Code'), __('Name')));
	foreach ($accounts as $account) {
		$ar = array(
			$this->Html->link($account['Account']['code'],
				array('action'=>'view',$account['Account']['id'])),
			$account['Account']['name_chi'] . '(' .
			$account['Account']['name']. ')');
		echo $this->Html->tableCells($ar,
			array('class'=>''),array('class'=>'altrow'));
	}
?>
</table>
