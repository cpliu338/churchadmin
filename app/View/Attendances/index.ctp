<?php 
    echo $this->Html->script(Configure::read('Js.jquery'), array('inline'=>false));
    echo $this->Html->script(Configure::read('Js.jquery-ui'), array('inline'=>false));
    echo $this->Html->css(Configure::read('Css.jquery-ui'), 'stylesheet', array('inline'=>false));
?>
<h2 id="note">Ready</h2>
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
        if (empty($member['t2']['time1'])) {
        	$time = '';
        	$cl = 'c1';
        }
        else {
        	// only time portion
        	$time = substr($member['t2']['time1'],10);
        	$cl = 'c2';
        }
        // width=240, alt=name
        $refresh = $this->Html->image('refresh.jpg', array('width'=>40));
        echo '<td>',$this->Html->image("http://therismos.dyndns.org/photos/$memberid.jpg",
        	array('id'=>"img$memberid",  'width'=>101, 'alt'=>$name, 'class'=>$cl)), 
        "<span>$name</span><a class='but' href='#' id='pos$memberid'>$refresh</a><br/>",
        "<span id='msg$memberid'>$time</span></td>";
        $c++;
    }
    prtGrpEnd();
?>
</div> <!-- of accordion-->
<?php
$script2=<<<SCRIPT2
$(function() {
	$('#accordion').accordion();
});
SCRIPT2;
	echo $this->Html->scriptBlock($script2, array('inline'=>false));
$script=<<<SCRIPT
        var id = $(this).attr('id'); 
        var value = $(this).prev().html(); 
        $.ajax({
            type: "POST",
            url: "$here/toggle",
            data: '{"Id": "' + id + '", "Value": "' + value + '"}',
            dataType: "json",
            contentType: "application/json; charset=utf-8",
            success: function (data) {
                $('#note').html(data.result);
                $(data.msgid).html(data.timestamp);
                $(data.imgid).removeClass(data.oldClass);
                $(data.imgid).addClass(data.newClass);
            }
        });
SCRIPT;
$this->Js->get(".but")->event(
    'click',
    $script);
    echo $this->Js->writeBuffer();
?>
