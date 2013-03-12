<?php 
$script1 =<<<SCRIPT
	$(function() {
    		$( "#TransactionDate1" ).datepicker({ dateFormat: "yy-mm-dd",
    		onClose: function() { $("#TransactionIndexForm").submit()}} );
    });
SCRIPT;
	echo $this->Html->css('//code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css','stylesheet',array('inline'=>false));
	echo $this->Html->script(Configure::read('Js.jquery'), array('inline'=>false));
	echo $this->Html->script(Configure::read('Js.jquery-ui'), array('inline'=>false));
	echo $this->element('menu', array('toggle'=>$toggle));
	echo $this->Form->create('Transaction'), 
		$this->Form->input('date1', array('label'=>__('Since'),'type'=>'text')),
		__('to'), "<span id='end_date'>$end_date</span>",
		"</form>";
		//$this->Form->end(__('Change'));
	echo $this->Html->scriptBlock($script1, array('inline'=>true));
	echo $this->element('entries1', array('transactions'=>$transactions));
?>

