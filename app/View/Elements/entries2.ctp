<table>
<?php
/**
 * Entries table for bank book like entries
 */
    $factor = (preg_match('/^[2|3|4]/', $account['Account']['code'])) ? 1 : -1;
    $tot = $bftotal * $factor;
    echo $this->Html->tableHeaders(array(__('Date'),__('Ref'),
        __('Account'),__('Amount'),__('Balance'), __('Detail')));
    $ar = array('','','','',$tot,__('Brought Forward'));
    echo $this->Html->tableCells($ar,
        array('class'=>''),array('class'=>'altrow'));
    foreach ($transactions as $transaction) {
        $tot += $transaction['Transaction']['amount'] * $factor;
        $ar = array(
            $transaction['Transaction']['date1'],
            $this->Html->link($transaction['Transaction']['tran_id'],
                array('action'=>'edit',$transaction['Transaction']['id'])),
            $transaction['Account']['name_chi'],
            $transaction['Transaction']['amount'] * $factor,
            $tot,
            $transaction['Transaction']['detail']);
        echo $this->Html->tableCells($ar,
                array('class'=>''),array('class'=>'altrow'));
    }
?>
</table>