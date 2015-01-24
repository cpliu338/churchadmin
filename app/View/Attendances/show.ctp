<?php 
	echo $this->element('menu', array('toggle'=>$toggle));
?>
<h3>Attendance List <?php echo $date1;?></h3>
<p>
<?php
echo $this->Html->link($order==1?'By group':'By time', array(
    'controller' => 'attendances',
    'action' => 'show',
    $date1,
    '?' => array('sort' => ($order==1 ? 'group' : 'time')))
);
?>
</p>
<ol>
<?php
    foreach ($items as $item) {
    	echo '<li>', $item['Member']['name'], substr($item['Attendance']['time1'], 10),
    	$item['Member']['groupname'],
    	"</li>\n";
    }
?>
</ol>
