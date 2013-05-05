<?php 
	echo $this->Html->css(Configure::read('Css.jquery-ui'),'stylesheet',array('inline'=>false));
	echo $this->Html->script(Configure::read('Js.jquery'), array('inline'=>false));
	echo $this->Html->script(Configure::read('Js.jquery-ui'), array('inline'=>false));
	echo $this->Html->script(array(
		'jquery.cascade.js', 'jquery.cascade.ext.js', 'jquery.templating.js'
		), false);
	echo $this->element('menu', array('toggle'=>$toggle));
	echo '<div>';
	echo $this->Html->link(__('Offers'), array('action'=>'index'));
	echo '</div>';
	$url1=$this->base . '/members/suggest.json';
$script1 = <<<SCRIPT1
function commonTemplate(item) {
    return "<option value='" + item.Value + "'>" + item.Text + "</option>"; 
};
function commonMatch(selectedValue) {
	return this.When == selectedValue; 
};
$(document).ready(function() {
	$("#OfferMemberId").cascade("#OfferName1",
	{
	ajax: { 
		url: '$url1', 
		data: { act: 'first', val: $('#OfferName1').val() }
	},				
	template: commonTemplate,
	match: commonMatch  			
	});
});
    $(function() {
    		$( "#OfferDate1" ).datepicker({ dateFormat: "yy-mm-dd",
    		});
    });

SCRIPT1;
	echo $this->Form->create('Offer');
?>
    <select id='OfferName1'>
<?php foreach ($name1 as $key=>$value): ?>
    <option value='<?php echo $key;?>'><?php echo $value;?></option>
<?php endforeach; ?>
</select>
<?php
    echo $this->Html->scriptBlock($script1, array('inline'=>false));
    echo $this->Form->input('member_id'),//array('options'=>$accounts,'id'=>'acc')),
        $this->Form->input('account_id'),
        $this->Form->input('amount'),
        $this->Form->input('date1',array('type'=>'text')),
        $this->Form->end(__('Submit'));
        //$base = $this->request->base;
?>