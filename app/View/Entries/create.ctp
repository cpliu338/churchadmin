<?php
$script1 =<<<SCRIPT
    $('#chequeno').html('Cheque no');
    \$v = $('#acc').val();
    $('#detail').val(\$v);
SCRIPT;
	echo $this->Html->css(Configure::read('Css.jquery-ui'),array('inline'=>false));
	echo $this->Html->script(Configure::read('Js.jquery'), array('inline'=>false));
	echo $this->Html->script(Configure::read('Js.jquery-ui'), array('inline'=>false));
    echo $this->element('menu', array('toggle'=>$toggle));
    $this->Js->get('#acc')->event('change', $script1);
    echo $this->Form->create('Entry'), 
            $this->Form->input('date1', array('type'=>'text')),
            $this->Form->input('account_id',array('options'=>$accounts,'id'=>'acc')),
            $this->Form->input('detail',array('id'=>'detail','label'=>array('id'=>'chequeno'))),
            $this->Form->end(__('Submit'));
    echo $this->Js->writeBuffer(array('inline'=>false));
?>