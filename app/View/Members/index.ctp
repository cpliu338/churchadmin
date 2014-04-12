<?php 
	echo $this->element('menu', array('toggle'=>$toggle)); 
	echo $this->Form->create('Member'), 
		$this->Form->input('name', array('label'=>__('Name contains'))),
		$this->Form->end('Submit');
?>	
<table>
<?php
	echo $this->Html->tableHeaders(array(__('nickname'),__('name'),__('action')));
	foreach ($members as $member) {
		echo $this->Html->tableCells(array(
			$this->Html->link($member['Member']['nickname'],
				array('action'=>'edit','admin'=>true,$member['Member']['id'])),
			$member['Member']['name'],
			$this->Html->link(__('offer'),
				array('action'=>'fromMember','controller'=>'offers',$member['Member']['id'])) . ' ' .
			$this->Html->link(__('attendance'),
				array('action'=>'ofMember','controller'=>'attendances',$member['Member']['id'])),
			),
			array('class'=>''),array('class'=>'altrow'));
	}
?>
</table>

