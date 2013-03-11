<?php 
$script1 =<<<SCRIPT
	$(function() {
    		$( "#TransactionDate1" ).datepicker({ dateFormat: "yy-mm-dd",
    		onClose: function() { $("#TransactionIndexForm").submit()}} );
    });
SCRIPT;
	echo $this->Html->script(Configure::read('Js.jquery'), array('inline'=>false));
	echo $this->Html->script(Configure::read('Js.jquery-ui'), array('inline'=>false));
	echo $this->element('menu', array('toggle'=>$toggle));
	echo $this->Form->create('Transaction'), 
		$this->Form->input('date1', array('label'=>__('Since'),'type'=>'text')),
		__('to'), "<span id='end_date'>$end_date</span>",
		//"</form>";
		$this->Js->submit(__('Change'),array(
			'url'=>array('action'=>'index','admin'=>true),
			'confirm'=>'Sure',
			'update'=>'#datatable'
			));
	//echo $this->Html->scriptBlock($script1, array('inline'=>true));
?>
<div id='datatable'>
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
</div>
<?php
	echo $this->Js->writeBuffer();
?>
