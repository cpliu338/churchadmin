<?php 
	echo $this->Html->css(Configure::read('Css.jquery-ui'),'stylesheet',array('inline'=>false));
	echo $this->Html->script(Configure::read('Js.jquery'), array('inline'=>false));
	echo $this->Html->script(Configure::read('Js.jquery-ui'), array('inline'=>false));
	echo $this->element('menu', array('toggle'=>$toggle));
//	echo $this->element('entries1', array('entries'=>$entries));
?>
<table>
<?php foreach ($entries as $entry): ?>
    <tr>
        <td><?php echo $entry['Entry']['id'];?></td>
        <td><?php echo $entry['Entry']['detail'];?></td>
        <td><?php echo $entry['Account']['name'];?></td>
        <td><?php echo $entry['Entry']['amount'];?></td>
    </tr>
<?php endforeach; ?>
</table>
<?php
	echo $this->Form->create('Entry'), 
                $this->Form->end('Submit');
        $base = $this->request->base;
?>
