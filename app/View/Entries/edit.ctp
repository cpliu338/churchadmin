<?php 
	echo $this->Html->css(Configure::read('Css.jquery-ui'),'stylesheet',array('inline'=>false));
	echo $this->Html->script(Configure::read('Js.jquery'), array('inline'=>false));
	echo $this->Html->script(Configure::read('Js.jquery-ui'), array('inline'=>false));
	echo $this->Html->script(array(
		'jquery.cascade.js', 'jquery.cascade.ext.js', 'jquery.templating.js'
		), false);
	echo $this->element('menu', array('toggle'=>$toggle));
        $shortfall = 0.0;
	$url1=$this->base . '/accounts/suggest.json';
$script1 = <<<SCRIPT1
function commonTemplate(item) {
    return "<option value='" + item.Value + "'>" + item.Text + "</option>"; 
};
function commonMatch(selectedValue) {
	return this.When == selectedValue; 
};
$(document).ready(function() {
	$("#EntryAccountId").cascade("#SpecialAccount1",
	{
	ajax: { 
		url: '$url1', 
		data: { act: 'first', val: $('#EntryAccount1').val() }
	},				
	template: commonTemplate,
	match: commonMatch  			
	});
        $('#EntryAmount').keyup(function() {
            s = Number($('#shortfall').val())+Number($('#EntryAmount').val())
            $('#CheckSum').val(s.toFixed(2));
            });
        $('#balance').click(function() {
            $('#CheckSum').val(0.00);
            $('#EntryAmount').val(0-Number($('#shortfall').val()));
        });
});
SCRIPT1;
?>
<table>
<?php foreach ($entries as $entry): ?>
    <tr>
        <td><?php echo $this->Html->link($entry['Entry']['id'], array('action'=>'edit',$entry['Entry']['id']));?></td>
        <td><?php echo $entry['Entry']['detail'];?></td>
        <td><?php echo $entry['Account']['name_chi'];?></td>
        <td>
<?php 
    if ($entry['Entry']['id']!=$this->data['Entry']['id'])
        $shortfall += $entry['Entry']['amount'];
        echo $entry['Entry']['amount'];
?>
        </td>
    </tr>
<?php endforeach; ?>
</table>
<?php
	if ($this->data['Entry']['id']) { // not null, i.e. edit, render "add" link
		echo $this->Html->link(__('Add'),array('action'=>'add', $entry['Entry']['transref']));
	}
	echo $this->Form->create('Entry');
        echo __('Date'), ': ', $entry['Entry']['date1'];//$this->Form->hidden('id'),
        //$this->Form->input('date1', array('type'=>'text','readonly'=>TRUE));
?>
<div>
    <label for="CheckSum">Check Sum</label>
    <input readonly="readonly" maxlength="13" value="<?php echo number_format($shortfall+$this->data['Entry']['amount'],2);?>" id="CheckSum"/>
</div>
    <select id='SpecialAccount1'>
<?php foreach ($options as $key=>$value): ?>
    <option value='<?php echo $key;?>'><?php echo $value;?></option>
<?php endforeach; ?>
</select>
<input type="hidden" value="<?php echo $shortfall;?>" id="shortfall"/>
<?php
    echo $this->Html->scriptBlock($script1, array('inline'=>false));
    echo $this->Form->input('account_id'),//array('options'=>$accounts,'id'=>'acc')),
        //$this->Form->hidden('transref'),
        $this->Form->input('amount');
?>
    <a href="#" id="balance">balance</a>
<?php
    echo $this->Form->input('detail'),
        $this->Form->hidden('extra1'),
        $this->Form->end(__('Update'));
	//$base = $this->request->base;
	if ($toggle)
		echo $this->Html->link('Admin edit',array('action'=>'edit',$this->data['Entry']['id'],'admin'=>true));
?>