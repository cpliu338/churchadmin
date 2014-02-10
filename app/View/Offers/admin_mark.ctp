<?php 
    echo $this->element('menu', array('toggle'=>$toggle));
?>
<h3><?php echo $prompt;?></h3>
<?php
    echo $this->Form->create(),
            $this->Form->input('receipt'),
    $this->Form->end(array('label'=>'Mark'));
?>
