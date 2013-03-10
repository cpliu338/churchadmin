<?php 
	echo $this->element('menu', array('toggle'=>$toggle)); 
?>	
<table>
<?php
	echo $this->Html->tableHeaders(array('nickname','name'));
	foreach ($members as $member) {
		echo $this->Html->tableCells(array(
			$member['Member']['nickname'],
			$member['Member']['name']
			),
			array('class'=>''),array('class'=>'altrow'));
	}
?>
</table>

