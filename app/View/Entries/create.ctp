<?php
$script1 =<<<SCRIPT
    \$v = $('#acc').val();
	if (\$v == '11201') {
    	$('#chequeno').html('Cheque no');
    	$('#detail').val('$cheqno');
	}
	else
		$('#chequeno').html('Detail');
SCRIPT;
	echo $this->Html->css(Configure::read('Css.jquery-ui'),'stylesheet',array('inline'=>false));
	echo $this->Html->script(Configure::read('Js.jquery'), array('inline'=>false));
	echo $this->Html->script(Configure::read('Js.jquery-ui'), array('inline'=>false));
    echo $this->element('menu', array('toggle'=>$toggle));
    $this->Js->get('#acc')->event('change', $script1);
$script2 =<<<SCRIPT2
    $(function() {
    		$( "#EntryDate1" ).datepicker({ dateFormat: "yy-mm-dd",
    		onClose: function() { $("#EntryIndexForm").submit()}} );
    });
SCRIPT2;
	echo $this->Html->scriptBlock($script2, array('inline'=>false));
    echo $this->Form->create('Entry'), 
            $this->Form->input('date1', array('type'=>'text')),
            $this->Form->input('account_id',array('options'=>$accounts,'id'=>'acc')),
            $this->Form->input('detail',array('id'=>'detail','label'=>array('id'=>'chequeno'))),
            $this->Form->input('amount'),
            $this->Form->end(__('Submit'));
    echo $this->Js->writeBuffer(array('inline'=>false));
?>