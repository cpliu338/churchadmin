<?php 
	echo $this->element('menu', array('toggle'=>$toggle));
?>
<h3>Attendance List <?php echo $date1;?></h3>
<ol>
<?php
    foreach ($items as $item) {
    	echo '<li>', $item['Member']['name'], substr($item['Attendance']['time1'], 10),
    	"</li>\n";
    }
?>
</ol>
