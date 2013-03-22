<table>
<?php
	echo
		$this->Html->tableHeaders(array(__('Date'),__('Ref'),
		__('Account'),'DB','CR', __('Detail')));
	foreach ($transactions as $transaction) {
		$ar = array(
			$transaction['Transaction']['date1'],
			$this->Html->link($transaction['Transaction']['tran_id'],
				array('action'=>'edit',$transaction['Transaction']['id'])),
			$transaction['Account']['name_chi']);
		$amt = $transaction['Transaction']['amount'];
		if ($amt<0) 
			array_push($ar,0-$amt,'');
		else
			array_push($ar,'',$amt);
		array_push($ar,$transaction['Transaction']['detail']);
		echo $this->Html->tableCells($ar,
			array('class'=>''),array('class'=>'altrow'));
	}
?>
</table>
