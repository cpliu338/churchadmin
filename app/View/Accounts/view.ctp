<?php 
$script1 =<<<SCRIPT
	$(function() {
    		$( "#EntryDate1" ).datepicker({ dateFormat: "yy-mm-dd",
    		onClose: function() { $("#EntryViewForm").submit()}} );
    });
SCRIPT;
	echo $this->Html->css('//code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css','stylesheet',array('inline'=>false));
	echo $this->Html->script(Configure::read('Js.jquery'), array('inline'=>false));
	echo $this->Html->script(Configure::read('Js.jquery-ui'), array('inline'=>false));
	echo $this->element('menu', array('toggle'=>$toggle));
?>
<h2><?php echo $account['Account']['code'], $account['Account']['name_chi'];?></h2>
<?php
	if (!empty($breadCrumb)) {
		echo '<div class="nav">';
		foreach ($breadCrumb as $ac) {
			echo $this->Html->link($ac['Account']['name_chi'],array($ac['Account']['id'])),' > ';
		}
		echo '</div>';
	}
	if (in_array($account['Account']['id'], array('11201'))) {
		echo '<div class="nav">';
		echo $this->Html->link(__('Vet'), array('controller'=>'entries', 'action'=>'vet', $account['Account']['id']));
		echo '</div>';
	}
	echo $this->Form->create('Entry'), 
		$this->Form->input('date1', array('label'=>__('Since'),'type'=>'text')),
		"</form>";
        echo $this->Html->scriptBlock($script1, array('inline'=>true));
        $bftotal = $broughtForward[0]['total'];
//        debug($bftotal);
	echo $this->element('entries2', array('entries'=>$entries, 'bftotal'=>$bftotal));
?>