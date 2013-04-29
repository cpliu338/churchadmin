<?php 
	echo $this->element('menu', array('toggle'=>$toggle));
    $this->Js->get('#but');
    $this->Js->event('click',
            $this->Js->request(
                array('controller'=>'entries','action' => 'totalize', '541'),
                array(
                    'async' => true, 'update' => '#total1')
            )
        );
    echo $this->Js->writeBuffer();
?>
<div><a href="#" id="but">click</a></div>
<div id="total1">
    loading...
</div>
<table>
<?php
	echo $this->Html->tableHeaders(array(__('Code'), __('Name'), __('Action')));
	foreach ($accounts as $account) {
		$ar = array(
			$this->Html->link($account['Account']['code'],
				array('action'=>'view',$account['Account']['id'])),
			$account['Account']['name_chi'] . '(' . $account['Account']['name']. ')',
                        $this->Html->link(__('Explore'),
				array('action'=>'index','?'=>
                                    array('under'=>$account['Account']['code']))
                        )
                    );
		echo $this->Html->tableCells($ar,
			array('class'=>''),array('class'=>'altrow'));
	}
?>
</table>

