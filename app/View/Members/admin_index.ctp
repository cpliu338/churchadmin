<?php 	echo $this->element('menu', array('toggle'=>$toggle)); ?>
<table>
<?php
	echo $this->Paginator->numbers();
	echo $this->Html->tableHeaders(array('name','nickname','groupname','level',
		'actions'));
	foreach ($members as $member) {
		echo $this->Html->tableCells(array(
			$member['Member']['name'],
			$member['Member']['nickname'],
			$member['Member']['groupname'],
			$member['Member']['level'],
			$this->Html->link('Edit',array(
				'action'=>'edit','admin'=>true,$member['Member']['id']))
				),
			array('class'=>''),array('class'=>'altrow'));
	}
?>
</table>
<?php
	echo $this->Html->link('Add',array('action'=>'add','admin'=>false));
?>
