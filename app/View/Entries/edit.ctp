<?php 
	echo $this->Html->css(Configure::read('Css.jquery-ui'),'stylesheet',array('inline'=>false));
	echo $this->Html->script(Configure::read('Js.jquery'), array('inline'=>false));
	echo $this->Html->script(Configure::read('Js.jquery-ui'), array('inline'=>false));
	echo $this->Html->script(array(
		'jquery.cascade.js',
		'jquery.cascade.ext.js',
		'jquery.templating.js'
		), false);
	echo $this->element('menu', array('toggle'=>$toggle));
	$url1=$this->base . '/accounts/suggest';
$script1 = <<<SCRIPT1
function commonTemplate(item) {
return "<option value='" + item.Value + "'>" + item.Text + "</option>"; 
};
function commonMatch(selectedValue) {
	return this.When == selectedValue; 
};

jQuery(document).ready(function() {
	jQuery("#EntryAccountId").cascade("#SpecialAccount1",
	{
	ajax: { 
		url: '$url1', 
		data: { act: 'first', val: $('#EntryAccount1').val() }
	},				
	template: commonTemplate,
	match: commonMatch  			
	});
});
SCRIPT1;
?>
<div style='width:200 px'><table>
<tr><td>Date:</td><td><?php echo $this->data['Entry']['date1'];?></td></tr>
</table></div>
<table>
<?php foreach ($entries as $entry): ?>
    <tr>
        <td><?php echo $this->Html->link($entry['Entry']['id'], array('action'=>'edit',$entry['Entry']['id']));?></td>
        <td><?php echo $entry['Entry']['detail'];?></td>
        <td><?php echo $entry['Account']['name'];?></td>
        <td><?php echo $entry['Entry']['amount'];?></td>
    </tr>
<?php endforeach; ?>
</table>
<?php
	echo $this->Form->create('Entry');
		echo "<select id='SpecialAccount1'>";
		foreach ($options as $key=>$value) {
			echo "<option value='$key'>$value</option>";
		}
		echo "</select>";
		echo $this->Html->scriptBlock($script1, array('inline'=>false));
		echo $this->Form->input('account_id'),//array('options'=>$accounts,'id'=>'acc')),
		$this->Form->input('amount'),
		$this->Form->input('detail'),
                $this->Form->end('Submit');
        $base = $this->request->base;
?>
