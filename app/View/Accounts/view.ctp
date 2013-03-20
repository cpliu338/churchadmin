<?php 
$script1 =<<<SCRIPT
	$(function() {
    		$( "#TransactionDate1" ).datepicker({ dateFormat: "yy-mm-dd",
    		onClose: function() { $("#TransactionViewForm").submit()}} );
    });
SCRIPT;
	echo $this->Html->css('//code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css','stylesheet',array('inline'=>false));
	echo $this->Html->script(Configure::read('Js.jquery'), array('inline'=>false));
	echo $this->Html->script(Configure::read('Js.jquery-ui'), array('inline'=>false));
	echo $this->element('menu', array('toggle'=>$toggle));
?>
<h2><?php echo $account['Account']['code'], $account['Account']['name_chi'];?></h2>
<?php
	echo $this->Form->create('Transaction'), 
		$this->Form->input('date1', array('label'=>__('Since'),'type'=>'text')),
		"</form>";
        echo $this->Html->scriptBlock($script1, array('inline'=>true));
        $bftotal = $broughtForward[0]['total'];
//        debug($bftotal);
	echo $this->element('entries2', array('transactions'=>$transactions, 'bftotal'=>$bftotal));
?>