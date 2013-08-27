<?php 
    echo $this->Html->script(Configure::read('Js.jquery'), array('inline'=>false));
?>
<form>
<?php
    foreach ($members as $member) {
        $memberid = $member['Member']['id'];
        $name = $member['Member']['name'];
        echo "<a href='#' id='foo$memberid'>$memberid</a>",
        "<span style='margin: 10px' id='element$memberid'>$name</span>";
$this->Js->get("#foo$memberid")->event(
    'click',
    $this->Js->request(
        array('action' => 'toggle', $memberid),
        array('async' => true, 'update' => "#element$memberid")
    )
);

    }
?>
</form>
<?php
    echo $this->Js->writeBuffer();
?>
