<?php 
	echo $this->Html->css(Configure::read('Css.jquery-ui'),'stylesheet',array('inline'=>false));
	echo $this->Html->script(Configure::read('Js.jquery'), array('inline'=>false));
	echo $this->Html->script(Configure::read('Js.jquery-ui'), array('inline'=>false));
	echo $this->Html->script(array(
		'jquery.cascade.js', 'jquery.cascade.ext.js', 'jquery.templating.js'
		), false);
	echo $this->element('menu', array('toggle'=>$toggle));
$script2 =<<<SCRIPT2
    $(function() {
    		$( "#EntryDate1" ).datepicker({ dateFormat: "yy-mm-dd",
    		} );
    });
SCRIPT2;
    echo $this->Html->scriptBlock($script2, array('inline'=>false));
?>
<table>
<?php foreach ($entries as $entry): ?>
    <tr>
        <td><?php echo $this->Html->link($entry['Entry']['id'], array('action'=>'edit',$entry['Entry']['id']));?></td>
        <td><?php echo $entry['Entry']['detail'];?></td>
        <td><?php echo $entry['Account']['name_chi'];?></td>
        <td><?php echo $entry['Entry']['amount'];?></td>
    </tr>
<?php endforeach; ?>
</table>
<?php
	echo $this->Form->create('Entry'),
		$this->Form->input('date1', array('type'=>'text'));
    echo $this->Form->input('extra1'),
        $this->Form->end(__('Update'));
//	if ($toggle)
	echo $this->Html->link('Normal edit',array('action'=>'edit',$entry['Entry']['id'],'admin'=>false));
?>