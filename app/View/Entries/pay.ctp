<?php
$script1 =<<<SCRIPT
    $('#EntryDetail').val($("#EntryChoice option:selected").text());
    if ($("#EntryChoice").val()>0) {
    	$('input[type="submit"]').prop('disabled',false);
    }
    else {
    	$('input[type="submit"]').prop('disabled',true);
    }
SCRIPT;
$label1 = ($isChecking) ? $cheque : $extra1;
    echo $this->Html->css(Configure::read('Css.jquery-ui'),'stylesheet',array('inline'=>false));
    echo $this->Html->script(Configure::read('Js.jquery'), array('inline'=>false));
    echo $this->Html->script(Configure::read('Js.jquery-ui'), array('inline'=>false));
    echo $this->element('menu', array('toggle'=>$toggle));
    $this->Js->get('#EntryChoice')->event('change', $script1);
$script2 =<<<SCRIPT2
    $(function() {
    		$( "#EntryDate1" ).datepicker({ dateFormat: "yy-mm-dd" });
    });
SCRIPT2;
    echo $this->Html->scriptBlock($script2, array('inline'=>false));
    echo $this->Form->create('Entry'), 
            $this->Form->select('choice', $entries),
            $this->Form->input('extra1',array('label'=>'Cheque No.')),
            $this->Form->input('detail',array('label'=>'Payee')),
            $this->Form->input('date1', array('type'=>'text')),
            $this->Form->end(__('Submit'));
    echo $this->Js->writeBuffer(array('inline'=>false));
?>