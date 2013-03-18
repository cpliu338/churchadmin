<?php 
	echo $this->element('menu', array('toggle'=>$toggle));
?>
<table>
<?php
	echo
		$this->Html->tableHeaders(array(__('Date'),__('Ref'),
		__('Account'),'DB','CR', __('Detail')));
	foreach ($accounts as $account) {
		$ar = array(
			$account['Account']['name'],
			$this->Html->link($account['Account']['code'],
				array('action'=>'view',$account['Account']['id'])),
			$account['Account']['name_chi']);
		echo $this->Html->tableCells($ar,
			array('class'=>''),array('class'=>'altrow'));
	}
?>
</table>

