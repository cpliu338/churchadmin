<?php echo $this->element('menu', array('toggle'=>$toggle));?>
<div>
<?php echo $this->Html->link(__('Add'), array('action'=>'add'));?>
</div>
<table style="width:400px">
<?php
    $this->append('css');
    echo "<style>.column-2 { text-align:right}</style>";
    $this->end();
    echo $this->Html->tableHeaders(array(__('Date'),array(__('Total') => array('class' => 'column-2'))));
    foreach ($offers as $offer) {
        echo $this->Html->tableCells(
            array(
                $this->Html->link($offer['Offer']['date1'],array('action'=>'view',$offer['Offer']['date1'])),
                $this->Number->format($offer[0]['total'], $numberOptions)
                    ),
            array('class'=>''),array('class'=>'altrow'),
                true /* useCount */);
    }
?>
</table>