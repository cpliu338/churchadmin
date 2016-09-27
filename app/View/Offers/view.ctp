<?php echo $this->element('menu', array('toggle'=>$toggle));?>
<h2><?php echo $date1?></h2>
<table>
<?php
    $this->append('css');
    echo "<style>.column-3 { text-align:right; width: 100px}</style>";
    $this->end();
    echo $this->Html->tableHeaders(array(__('Account'),
        __('Name'),array(__('Amount') => array('class' => 'column-3')),__('Action')));
    $oId = 0;
    $oName = '';
    $total = 0;
    $subtotal = 0;
    $nUnposted = 0;
    foreach ($offers as $offer) {
    	if (!$offer['Offer']['posted']) $nUnposted++;
        if ($oId != $offer['Account']['id']) {
            if ($oId != 0) {
                echo $this->Html->tableHeaders(array($oName,
                    __('Total'),
                    $this->Number->format($subtotal, $numberOptions)
                    , ''
                ));
            }
            $oId = $offer['Account']['id'];
            $oName = $offer['Account']['name_chi'];
            $subtotal = 0;
        }
        $total += $offer['Offer']['amount'];
        $subtotal += $offer['Offer']['amount'];
        echo $this->Html->tableCells(array(
            $offer['Account']['name_chi'],
            $offer['Member']['name'],
            $this->Number->format($offer['Offer']['amount'], $numberOptions),
            $this->Html->link(__('Edit'), array('action'=>'edit', 'admin'=>$offer['Offer']['posted'], 
            	$offer['Offer']['id']))
            ),
            array('class'=>''),array('class'=>'altrow'),
                true /* useCount */);
    }
    echo $this->Html->tableHeaders(array($oName,__('Total'),
        $this->Number->format($subtotal, $numberOptions), ''
    ));
    echo $this->Html->tableHeaders(array($date1,__('Grand Total'),
        $this->Number->format($total, $numberOptions), ''
    ));
?>
</table>
<p>
<a href="http://<?=$host?>:<?=$port?>/choffice/WeeklyOffer?type=xlsx&datestr=<?=$date1?>"><?=__('Weekly Report')?></a>
</p>
<?php if ($nUnposted > 0): ?> 
<nav>
<?php echo $this->Html->link(__('Post'), array('action'=>'post', $date1));?>
</nav>
<?php endif; ?>