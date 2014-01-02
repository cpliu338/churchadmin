<table>
<?php
    $this->append('css');
    echo "<style>.column-4 { text-align:right}</style>";
    echo "<style>.column-5 { text-align:right}</style>";
    $this->end();

	echo
		$this->Html->tableHeaders(array(__('Date'),__('Ref'),
		__('Account'),'DB','CR', __('Detail'), __('Extra')));
	foreach ($entries as $entry) {
		$ar = array(
			$entry['Entry']['date1'],
			$this->Html->link($entry['Entry']['transref'],
				array('action'=>'edit',$entry['Entry']['id'])),
			$this->Html->link($entry['Account']['name_chi'],
				array('controller'=>'accounts','action'=>'view',$entry['Account']['id']))
			);
		$amt = $entry['Entry']['amount'];
		if ($amt<0) 
			array_push($ar, $this->Number->format(0-$amt, $numberOptions),'');
		else
			array_push($ar,'',$this->Number->format($amt, $numberOptions));
		array_push($ar,$entry['Entry']['detail'],$entry['Entry']['extra1']);
		echo $this->Html->tableCells($ar,
			array('class'=>''),array('class'=>'altrow'),true /* useCount */);
	}
?>
</table>
