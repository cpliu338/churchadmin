<?php 
    echo $this->Html->script(Configure::read('Js.jquery'), array('inline'=>false));
    echo $this->Html->script(Configure::read('Js.jquery-ui'), array('inline'=>false));
    echo $this->Html->css(Configure::read('Css.jquery-ui'), 'stylesheet', array('inline'=>false));
?>
<div id='accordion'>
<?php
function prtGrpStart($grp) {
	echo "<h3>$grp</h3>\n<div><table><tr>";
}
function prtGrpEnd() {
	echo "</tr></table></div>";
}
	$c = 0;
	$grp = '';
	$start = true;
    foreach ($members as $member) {
    	if ($member['member2']['groupname'] != $grp) {
			if (empty($start)) {
				prtGrpEnd();
			}
			else {
				$start = '';
			}
			$c = 0;
    		$grp = $member['member2']['groupname'];
    		prtGrpStart($grp);
    	}
    	if ($c == 3) {
    		echo '</tr><tr>';
    		$c=0;
    	}
        $memberid = $member['member2']['id'];
        $name = $member['member2']['name'];
        $time = empty($member['t2']['time1']) ? '' : $member['t2']['time1'];
        echo '<td>',$this->Html->image("http://therismos.dyndns.org:8080/choffice/members/card.jsf?primefacesDynamicContent=streamedBean.photo&id=$memberid",
        	array('id'=>"img$memberid",  'class'=>'c1')), 
        "$name<br/><a class='but' href='#' id='pos$memberid'>Change</a><br/>",
        "<span id='msg$memberid'>$time</span></td>";
        $c++;
    }
    prtGrpEnd();
?>
</div> <!-- of accordion-->
<div id="note"></div>
<?php
$script2=<<<SCRIPT2
$(function() {
	$('#accordion').accordion();
});
SCRIPT2;
	echo $this->Html->scriptBlock($script2, array('inline'=>false));
$script=<<<SCRIPT
        var id = $(this).attr('id'); 
        var value = $(this).val(); 
        $.ajax({
            type: "POST",
            url: "/choffice/attendances/toggle",
            data: '{"Id": "' + id + '", "Value": "' + value + '"}',
            dataType: "json",
            contentType: "application/json; charset=utf-8",
            success: function (data) {
                $('#note').html(data.note);
                $(data.imgid).removeClass('c1');
                $(data.imgid).addClass('c2');
            }
        });
SCRIPT;
$this->Js->get(".but")->event(
    'click',
    $script);
    echo $this->Js->writeBuffer();
?>
