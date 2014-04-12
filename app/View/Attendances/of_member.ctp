<?php echo $this->element('menu', array('toggle'=>$toggle));?>
<div>
<?php
	if (empty($attendances)) {
		echo 'No one';
	}
	else {
		echo $attendances[0]['Member']['name'];
	}
?>
</div>
<table style="width:400px">
<?php
    echo $this->Html->tableHeaders(array(__('Number'),__('Time')));
    //foreach ($attendances as $attendance) {
	for ($i=0; $i<count($attendances); $i++) {
		$attendance = $attendances[$i];
        echo $this->Html->tableCells(
            array(
                $i+1,
                $attendance['Attendance']['time1']
                    ),
            array('class'=>''),array('class'=>'altrow'),
                true /* useCount */);
    }
?>
</table>