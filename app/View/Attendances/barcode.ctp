<?php
    echo $this->Html->script(Configure::read('Js.jquery'), array('inline'=>false));
    echo $this->Html->script(Configure::read('Js.jquery-ui'), array('inline'=>false));
    echo $this->Html->css(Configure::read('Css.jquery-ui'), 'stylesheet', array('inline'=>false));
$script=<<<SCRIPT
		$("#member_id").val($(this).attr("id"));
		$("#form1").submit();
SCRIPT;
$this->Js->get(".del")->event(
    'click',
    $script);
$script2=<<<SCRIPT2
	if ($(this).val().length >= 8) {
        $.ajax({
            type: "POST",
            url: "$base/attendances/barcode",
            data: '{"Id": "' + $(this).val() + '"}',
            dataType: "json",
            contentType: "application/json; charset=utf-8",
            success: function (data) {
                $('#result').html(data.text+","+$('#result').html());
                $('#barcode').val('');
            }
        });
	}
SCRIPT2;
$this->Js->get("#barcode")->event(
    'keyup',
    $script2);
?>
<nav>
<?php echo $this->Html->link('By photos',array('action'=>'index'));?> |
<?= $this->Html->link('Absentees',array('action'=>'absentee')) ?>
</nav>
<input style="width: 50%; margin-bottom:5px; background-color:#ff8" id='barcode' size='10' autofocus/>
<div style="color: #00f; font-size:large" id='result'></div>
<p>Total <?php echo $cnt; ?> on <?php echo $today; ?></p>
<form id="form1" action="barcode" method="POST"><input type="hidden" id="member_id" name="memberid" value=""/></form>
<table style="width:400px">
<?php
	foreach ($attendances as $record) {
		echo $this->Html->tableCells(array(
			$record['Member']['name'], substr($record['Attendance']['time1'],10),
			$this->Html->link('delete','',array('id'=>$record['Member']['id'],'class'=>'del'))
		));
	}
?>
</table>
<?php 
    echo $this->Js->writeBuffer(['inline'=>false]);
?>
