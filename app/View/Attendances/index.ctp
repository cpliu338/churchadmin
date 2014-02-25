<?php 
    echo $this->Html->script(Configure::read('Js.jquery'), array('inline'=>false));
    echo $this->Html->script(Configure::read('Js.jquery-ui'), array('inline'=>false));
    echo $this->Html->css(Configure::read('Css.jquery-ui'), 'stylesheet', array('inline'=>false));
?>
<h2>
    <span id="note">Total: <?php echo $total;?></span>
    <span style="float:right"><?php echo $this->Html->link(
        $type==0 ? '按姓氏' : '按組別',
        array('action'=>'index',$type==0 ?1:0));
    echo ' | ', $this->Html->link('Barcode',array('action'=>'barcode'));
    ?></span>
</h2>
<?php
function tabStart($id) {
	return "<div id='$id'><table><tr>";
}
function tabEnd() {
	return "</tr></table></div>";
}
    $max = 4;
    $buf = '';
	$c = 0;
	$grp = 'iurghops87es'; // the impossible
        $serial = 0;
	$start = true;
        $tabindexes = array();
        $refresh = $this->Html->image('refresh.jpg', array('width'=>40));
    foreach ($members as $member) {
    	if ($member['member2']['grp'] != $grp) {
            if (empty($start)) {
                    $buf .= tabEnd();
            }
            else {
                    $start = '';
            }
            $grp = $member['member2']['grp'];
            $serial++;
            $c = 0;
            $buf .= tabStart("tab$serial");
            array_push($tabindexes, $this->Html->link($grp, "#tab$serial"));
    	}
    	if ($c == $max) {
            $buf .= '</tr><tr>';
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
        // width=101, alt=name
        $buf .= '<td>';
        $buf .= $this->Html->image("http://".$_SERVER['SERVER_NAME']."/photos/$memberid.jpg",
        	array('id'=>"img$memberid",  'width'=>101, 'alt'=>$name, 'class'=>$cl)); 
        $buf .= "<span>$name</span><a class='but' href='#' id='pos$memberid'>$refresh</a><br/>";
        $buf .= "<span id='msg$memberid'>$time</span></td>";
        $c++;
    }
    $buf.=tabEnd();
?>
<div id='tabs'>
<?php
    echo $this->Html->nestedList($tabindexes), $buf;
?>
</div>
<?php
$script2=<<<SCRIPT2
$(function() {
	$('#tabs').tabs();
});
SCRIPT2;
	echo $this->Html->scriptBlock($script2, array('inline'=>false));
$script=<<<SCRIPT
        var id = $(this).attr('id'); 
        var value = $(this).prev().html(); 
        $.ajax({
            type: "POST",
            url: "$base/attendances/toggle",
            data: '{"Id": "' + id + '", "Value": "' + value + '"}',
            dataType: "json",
            contentType: "application/json; charset=utf-8",
            success: function (data) {
                $('#note').html(data.result+'Total '+data.total);
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
