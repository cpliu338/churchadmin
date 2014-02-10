<?php 
	echo $this->Html->css(Configure::read('Css.jquery-ui'),'stylesheet',array('inline'=>false));
	echo $this->Html->script(Configure::read('Js.jquery'), array('inline'=>false));
	echo $this->Html->script(Configure::read('Js.jquery-ui'), array('inline'=>false));
	echo $this->element('menu', array('toggle'=>$toggle));
?>
<h2><?php echo __('Unbalanced'), ' ', __('Entries');?></h2>
<?php
	if (count($entries) + count($entries1) + count($entries2) == 0)
		echo '<p>Perfect, no abnormal entries</p>';
	echo '<table>',
		$this->Html->tableHeaders(array(__('Date'),__('Ref'),
		__('Amount'), __('Remark')));
	foreach ($entries as $entry) {
		$ar = array($entry['Entry']['date1']);
		array_push($ar,  $this->Html->link($entry['Entry']['transref'],
				array('action'=>'edit','admin'=>false, $entry['Entry']['id'])));
		array_push($ar, $entry[0]['suma'], $entry[0]['name']);
		echo $this->Html->tableCells($ar);
	}
	foreach ($entries1 as $entry) {
		$ar = array($entry['Entry']['date1']);
		array_push($ar,  $this->Html->link($entry['Entry']['transref'],
				array('action'=>'edit','admin'=>false, $entry['Entry']['id'])));
		array_push($ar, $entry['Entry']['amount'], $entry[0]['name'].$entry['Entry']['detail']);
		echo $this->Html->tableCells($ar);
	}
	foreach ($entries2 as $entry) {
		$ar = array($entry['Entry']['date1']);
		array_push($ar,  $this->Html->link($entry['Entry']['transref'],
				array('action'=>'edit','admin'=>false, $entry['Entry']['id'])));
		array_push($ar, $entry['Entry']['amount'], $entry[0]['name'].$entry['Entry']['detail']);
		echo $this->Html->tableCells($ar);
	}
?>
</table>
