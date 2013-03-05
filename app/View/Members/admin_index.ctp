<table>
<?php
	echo $this->Html->tableHeaders(array('nickname','level',
		'actions'));
	foreach ($members as $member) {
		echo $this->Html->tableCells(array(
			$member['Member']['nickname'],
			$member['Member']['level'],
			$this->Html->link('Edit',array(
				'action'=>'edit','admin'=>true,$member['Member']['id']))
				),
			array('class'=>''),array('class'=>'altrow'));
	}
?>
</table>

