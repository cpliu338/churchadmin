<?php 
	echo $this->Html->css(Configure::read('Css.jquery-ui'),'stylesheet',array('inline'=>false));
	echo $this->Html->script(Configure::read('Js.jquery'), array('inline'=>false));
	echo $this->Html->script(Configure::read('Js.jquery-ui'), array('inline'=>false));
	echo $this->element('menu', array('toggle'=>$toggle));
?>
<h2><?php echo __('Attendances'), ' ', __('from'), ' ', $start_date, __('to'), '', $end_date;?></h2>
<h3>To specify another start date, change URL to /admin/attendances?start_date=YYYY-mm-dd</h3>
<table style="width:400px">
<?php
    foreach ($attendances as $item) {
        echo $this->Html->tableCells(
            array($item['Member']['name'], 
            	$item[0]['cnt']
		)
	    );
    }
?>
</table>
