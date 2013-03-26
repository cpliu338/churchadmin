<?php
    echo $this->element('menu', array('toggle'=>$toggle));
    echo $this->Form->create('Entry'), 
            $this->Form->input('date1', array('type'=>'text')),
            $this->Form->input('account_id',array('options'=>$accounts)),
            $this->Form->input('detail',array('label'=>array('id'=>'chequeno'))),
            $this->Form->end(__('Submit'));
    debug($accounts);
?>