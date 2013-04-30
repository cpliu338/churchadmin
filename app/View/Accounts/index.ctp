<?php 
    $this->append('css');
    echo "<style>.column-3 { text-align:right}</style>";
    echo "<style>.column-3 span {margin-right: 50px}</style>";
    $this->end();
	echo $this->Html->script(Configure::read('Js.jquery'), array('inline'=>false));
	echo $this->element('menu', array('toggle'=>$toggle));
$format = '$.ajax({async:true,success:function(data,textStatus){$("#total%s").html(data);},url:"\/office\/entries\/totalize\/%s"});';
$script1 =<<<SCRIPT1
    $(document).ready(
        function() { 
SCRIPT1;
$script2 =<<<SCRIPT2
        }
    );
SCRIPT2;
    $snippet ='';
    foreach ($accounts as $account) {
        $snippet = $snippet. __($format, $account['Account']['code'], $account['Account']['code'])."\n";
    }
    echo $this->Html->scriptBlock("$script1\n$snippet\n$script2", array('inline'=>false));
?>
<table>
<?php
    $header_total=__("From %s to %s", $yearStart, $yearEnd);
    echo $this->Html->tableHeaders(array(__('Code'), __('Name'),$header_total, __('Action')));
    foreach ($accounts as $account) {
        $ar = array(
            $account['Account']['code'],
            $account['Account']['name_chi'] . '(' . $account['Account']['name']. ')',
            __("<span id='total%s'>loading...</span>",$account['Account']['code']),
            $this->Html->link(__('Entries'),array('action'=>'view',$account['Account']['id']))
            . ' | ' .
            $this->Html->link(__('Explore'),array('action'=>'index','?'=>array('under'=>$account['Account']['code'])))
        );
        echo $this->Html->tableCells($ar,
            array('class'=>''),array('class'=>'altrow'),true /* useCount */);
    }
?>
</table>

