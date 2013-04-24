<?php 
	echo $this->Html->css(Configure::read('Css.jquery-ui'),'stylesheet',array('inline'=>false));
	echo $this->Html->css('jquery-picklist','stylesheet',array('inline'=>false));
	echo $this->Html->script(Configure::read('Js.jquery'), array('inline'=>false));
	echo $this->Html->script(Configure::read('Js.jquery-ui'), array('inline'=>false));
	echo $this->Html->script(array(
		'jquery-picklist.min.js'
		), false);
	echo $this->element('menu', array('toggle'=>$toggle));
$script1 = <<<SCRIPT1
$(function() {
	$("#cheques").pickList(
	{
		sourceListLabel:	"Signed",
		targetListLabel:	"Cleared",
	});
	$("#cheques").bind("picklist_onchange", changeHandler)
});
	function changeHandler(event, obj) {
		var itemIds = [];
		var accum=new Number(0.0);
		obj.items.each(function()
		{
			var v = $(this).text();//attr("data-value");
			var index=v.lastIndexOf(":");
			accum=accum+new Number(v.substring(index+1));//new Number(v.substring(index));
		});
		$("#checksum").html(accum);
	}
SCRIPT1;
    echo $this->Html->scriptBlock($script1, array('inline'=>false));
	echo $this->Form->create('Entry');
?>
<div id='checksum'>0</div>
<select id="cheques" name='EntryId[]' multiple="multiple" style="clear:none" size='5'>
<?php
	foreach ($entries as $entry) {
		echo __("<option value='%d'>%s:%s</option>",$entry['Entry']['id'], 
			substr($entry['Entry']['extra1'],1),$entry['Entry']['amount']
	);
	}
?>
</select>
<?php
	echo $this->Form->end('Submit');
?>
	

