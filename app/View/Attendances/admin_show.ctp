<?php 
	echo $this->element('menu', array('toggle'=>$toggle));
?>
<h3>Absentee List <?php echo $date1;?></h3>
<dl>
<dt>Query</dt>
<dd><?php echo $query ?></dd>
</dl>
<div>
<?php
    foreach ($absentees as $item) {
    	echo '<span>', $item['Member']['name'],
    	"</span>\n";
    }
/*
    */
?>
</div>
