<?php 
	echo $this->element('menu', array('toggle'=>$toggle));
?>
<h3>Absentee List <?php echo $date1;?></h3>
<nav>
<?php echo $this->Html->link('By photos',array('action'=>'index'));?> |
<?= $this->Html->link('Barcode',array('action'=>'barcode')) ?>
</nav>
<div>
<?php foreach ($absentees as $item): ?>
    	<?= $this->Form->postLink($item['Member']['name'], ['action'=>'toggle',$item['Attendance']['member_id']], ['confirm'=>'Add '.$item['Member']['name'].'?']) ?>
<?php endforeach ?>
</div>
