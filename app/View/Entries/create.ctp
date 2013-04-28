<?php
$script1 =<<<SCRIPT
    \$v = $('#EntryAccountId').val();
    if (\$v == '11201') {
    	$('#chequeno').html('$cheque');
    	$('#EntryExtra1').val('$cheqno');
        $('#EntryExtra1').attr('readonly',false);
    }
    else {
        $('#chequeno').html('$extra1');
        $('#EntryExtra1').val('');
        $('#EntryExtra1').attr('readonly',true);
    }
SCRIPT;
$label1 = ($isChecking) ? $cheque : $extra1;
    echo $this->Html->css(Configure::read('Css.jquery-ui'),'stylesheet',array('inline'=>false));
    echo $this->Html->script(Configure::read('Js.jquery'), array('inline'=>false));
    echo $this->Html->script(Configure::read('Js.jquery-ui'), array('inline'=>false));
    echo $this->element('menu', array('toggle'=>$toggle));
    $this->Js->get('#EntryAccountId')->event('change', $script1);
$script2 =<<<SCRIPT2
    $(function() {
    		$( "#EntryDate1" ).datepicker({ dateFormat: "yy-mm-dd",
    		onClose: function() { $("#EntryIndexForm").submit()}} );
    });
SCRIPT2;
    echo $this->Html->scriptBlock($script2, array('inline'=>false));
    echo $this->Form->create('Entry'), 
            $this->Form->input('date1', array('type'=>'text')),
            $this->Form->input('account_id',array('options'=>$accounts)),
            $this->Form->input('detail',array('label'=>'Payee')),
            $this->Form->input('extra1',array('label'=>array('text'=>$label1,
                'disabled'=>!$isChecking,'id'=>'chequeno'))),
            $this->Form->input('amount'),
            $this->Form->end(__('Submit'));
    echo $this->Js->writeBuffer(array('inline'=>false));
?>