<?php
$script1 =<<<SCRIPT
jQuery(document).ready(function() {
	$('#MemberPrint').click(function() {
		$('#MemberPwd').attr('disabled',!$(this).is(':checked'));
	});
});
SCRIPT;
	echo $this->Html->script(Configure::read('Js.jquery'), array('inline'=>false)),
	$this->Html->scriptBlock($script1, array('inline'=>false)),
	$this->Form->create('Member'),
	$this->Form->input('nickname'),
	$this->Form->input('level'),
	$this->Form->input('print', array('label' => __('Reset password'), 'type'=>'checkbox')),
	$this->Form->input('pwd', array('type' => 'password', 'disabled'=>true)),
	$this->Form->end(__('Update'));
?>
