<?php 
	echo $this->element('menu', array('toggle'=>$toggle));
?>
<h3>Recent Attendances</h3>
<nav>
<?php echo $this->Html->link('recent 20', array('action'=>'stat',20));?> |
<?php echo $this->Html->link('recent 30', array('action'=>'stat',30));?>
</nav>
<table style="width:400px">
<?php
    foreach ($stat as $item) {
        echo $this->Html->tableCells(
            array($this->Html->link($item[0]['date1'], 
            	array('action'=>'show',$item[0]['date1'])),
            	$item[0]['cnt'],
            	$this->Html->link('absentees',
            	array('action'=>'show','admin'=>true, $item[0]['date1'])),
			)
		);
    }
?>
</table>

