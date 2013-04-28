<table>
<?php
    $this->append('css');
    echo "<style>.column-4 { text-align:right}</style>";
    echo "<style>.column-5 { text-align:right}</style>";
    $this->end();

/**
 * Entries table for bank book like entries
 */
    $factor = (preg_match('/^[2|3|4]/', $account['Account']['code'])) ? 1 : -1;
    $tot = $bftotal * $factor;
    echo $this->Html->tableHeaders(array(__('Date'),__('Ref'),
        __('Account'),__('Amount'),__('Balance'), __('Detail'), __('Extra')));
    $ar = array('','','','',$tot,__('Brought Forward'));
    echo $this->Html->tableCells($ar,
        array('class'=>''),array('class'=>'altrow'));
    foreach ($entries as $entry) {
        $tot += $entry['Entry']['amount'] * $factor;
        $ar = array(
            $entry['Entry']['date1'],
            $this->Html->link($entry['Entry']['transref'],
                array('controller'=>'entries','action'=>'edit',$entry['Entry']['id'])),
            $entry['Account']['name_chi'],
            $this->Number->format($entry['Entry']['amount'] * $factor, $numberOptions),
            $this->Number->format($tot, $numberOptions),
            $entry['Entry']['detail'],
            $entry['Entry']['extra1']);
        echo $this->Html->tableCells($ar,
                array('class'=>''),array('class'=>'altrow'), true);
    }
?>
</table>