<?php 
    echo $this->element('menu', array('toggle'=>$toggle));
$script1 =<<<SCRIPT
$(document).ready(function() {
        $("#receipt").val($option);
    $('#receipt').change(
        function(){
             $(this).closest('form').trigger('submit');
             /* or:
             $('#formElementId').trigger('submit');
                or:
             $('#formElementId').submit();
             */
    });
});
SCRIPT;
    echo $this->Html->script(Configure::read('Js.jquery'), array('inline'=>false));
    echo $this->Html->scriptBlock($script1, array('inline'=>false));
?>
<h3>Posted offers</h3>
<form id="form1" method="get" action='<?php echo "$base/admin/offers/index";?>'>
    <h4>
        Receipt batch: <select name="receipt" id="receipt">
<?php for($i=0; $i<$maxbatch; $i++): ?>
        <option value='<?php echo $i;?>'><?php echo $i;?></option>
<?php endfor; ?>
    </select>
    </h4>
</form>
<table style="width:400px">
<?php
    echo $this->Paginator->numbers();
    echo $this->Html->tableHeaders(array($this->Paginator->sort('date1'),$this->Paginator->sort('Member.name'),__('Account'),__('Amount')));
    foreach ($offers as $offer) {
        echo $this->Html->tableCells(
            array(
                $this->Html->link($offer['Offer']['date1'], array('action'=>'mark', 'date', $offer['Offer']['id'])),
                $this->Html->link($offer['Member']['name'], array('action'=>'mark', 'member', $offer['Offer']['id'])),
                $offer['Account']['name_chi'],
                $offer['Offer']['amount']
                    ),
            array('class'=>''),array('class'=>'altrow'),
                true /* useCount */);
    }
?>
</table>