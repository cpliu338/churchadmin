<?xml version="1.0" encoding="UTF-8" ?>
<offers date="<?php echo $offers[0]['Offer']['date1'];?>">
<?php foreach ($offers as $offer): ?>
<offer anonymous="false" posted="<?php echo empty($offer['Offer']['posted'])?'false':'true';?>">
<account><?php echo $offer['Account']['name_chi'];?></account>
<amount><?php echo $offer['Offer']['amount'];?></amount>
<name><?php echo $offer['Member']['name'];?></name>
</offer>
<?php endforeach;?>
</offers>
