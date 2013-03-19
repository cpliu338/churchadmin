<?php 
	echo $this->element('menu', array('toggle'=>$toggle));
?>
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

