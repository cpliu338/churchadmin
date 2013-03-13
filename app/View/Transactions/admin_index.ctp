<?php 
	
$script1 =<<<SCRIPT
	$(function() {
    		$( "#TransactionDate1" ).datepicker({ dateFormat: "yy-mm-dd"} );
    });
SCRIPT;
	echo $this->Html->css('//code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css','stylesheet',array('inline'=>false));
	echo $this->Html->script(Configure::read('Js.jquery'), array('inline'=>false));
	echo $this->Html->script(Configure::read('Js.jquery-ui'), array('inline'=>false));
	echo $this->element('menu', array('toggle'=>$toggle));
	echo $this->Form->create('Transaction', array('id'=>'inline')), 
		$this->Form->input('date1', array('label'=>__('From'),'type'=>'text')),
		__('to'), "<span id='end_date'>$end_date</span>",
		$this->Form->input('detail');
		//"</form>";
		/*
		$this->Js->submit(__('Change'),array(
			'url'=>array('action'=>'index','admin'=>true),
			'update'=>'#datatable'
			));
			*/
	echo $this->Form->end(__('Change'));
	$this->Paginator->options(array('update' => '#datatable'));
	echo $this->Paginator->numbers();
	//echo '</form>';
	echo $this->Html->scriptBlock($script1, array('inline'=>true));
?>
<div id='datatable'>
<?php echo $this->element('entries1', array('transactions'=>$transactions)); ?>
</div>
<?php echo $this->Js->writeBuffer(); ?>



