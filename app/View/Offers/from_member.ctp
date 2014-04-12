<?php echo $this->element('menu', array('toggle'=>$toggle));?>
<div>
<?php
	if (empty($offers)) {
		echo 'No one';
	}
	else {
		echo $offers[0]['Member']['name'];
	}
?>
</div>
<table style="width:400px">
<?php
    $this->append('css');
    echo "<style>.column-2 { text-align:right}</style>";
    $this->end();
    echo $this->Html->tableHeaders(array(__('Date'),array(__('Amount') => array('class' => 'column-2')),
    	__('Account'),__('Receipt'),__('Posted')));
    foreach ($offers as $offer) {
        echo $this->Html->tableCells(
            array(
                $offer['Offer']['date1'],
                $offer['Offer']['amount'],
                $offer['Account']['name_chi'],
                $offer['Offer']['receipt'],
                $offer['Offer']['posted']
                    ),
            array('class'=>''),array('class'=>'altrow'),
                true /* useCount */);
    }
?>
</table>