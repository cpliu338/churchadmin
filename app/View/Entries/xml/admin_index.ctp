<entries>
<?php foreach ($entries as $entry): ?>
<entry>
<account><?php echo $entry['Account']['name_chi'];?></account>
<code><?php echo $entry['Account']['code'];?></code>
<amount><?php echo $entry['Entry']['amount'];?></amount>
<id><?php echo $entry['Entry']['id'];?></id>
<transref><?php echo $entry['Entry']['transref'];?></transref>
<date1><?php echo $entry['Entry']['date1'];?></date1>
<detail><?php echo htmlspecialchars($entry['Entry']['detail']);?></detail>
</entry>
<?php endforeach;?>
</entries>
