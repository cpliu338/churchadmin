<?php echo $this->element('menu', array('toggle'=>$toggle));?>
<h2><?php echo $date1?></h2>
<table>
<?php
    $this->append('css');
    echo "<style>.column-3 { text-align:right; width: 100px}</style>";
    $this->end();
    echo $this->Html->tableHeaders(array(__('Account'),
        __('Name'),array(__('Amount') => array('class' => 'column-3'))));
    foreach ($offers as $offer) {
        echo $this->Html->tableCells(array(
            $offer['Account']['name_chi'],
            $offer['Member']['name'],
            $this->Number->format($offer['Offer']['amount'], $numberOptions),
            $offer['Offer']['posted']
            ),
            array('class'=>''),array('class'=>'altrow'),
                true /* useCount */);
    }
?>
</table>