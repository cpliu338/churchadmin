<?php 
	echo $this->Html->css(Configure::read('Css.jquery-ui'),'stylesheet',array('inline'=>false));
	echo $this->Html->script(Configure::read('Js.jquery'), array('inline'=>false));
	echo $this->Html->script(Configure::read('Js.jquery-ui'), array('inline'=>false));
	echo $this->Html->script(array(
		'jquery.cascade.js', 'jquery.cascade.ext.js', 'jquery.templating.js'
		), false);
	echo $this->element('menu', array('toggle'=>$toggle));
        $shortfall = 0.0;
$script1 = <<<SCRIPT1
$(document).ready(function() {
        $('#EntryAmount').keyup(function() {
            s = Number($('#shortfall').val())+Number($('#EntryAmount').val())
            $('#CheckSum').val(s.toFixed(2));
            });
        $('#balance').click(function() {
            $('#CheckSum').val(0.00);
            $('#EntryAmount').val(0-Number($('#shortfall').val()));
        });
});
SCRIPT1;
?>
<table>
<?php foreach ($entries as $entry): ?>
    <tr>
        <td><?php echo $this->Html->link($entry['Entry']['id'], array('action'=>'edit',$entry['Entry']['id']));?></td>
        <td><?php echo $entry['Entry']['detail'];?></td>
        <td><?php echo $entry['Account']['name_chi'];?></td>
        <td>
<?php 
    if ($entry['Entry']['id']!=$this->data['Entry']['id'])
        $shortfall += $entry['Entry']['amount'];
        echo $entry['Entry']['amount'];
?>
        </td>
    </tr>
<?php endforeach; ?>
</table>
<?php
	if ($this->data['Entry']['id']) { // not null, i.e. edit, render "add" link
		echo $this->Html->link(__('Add'),array('action'=>'add', $entry['Entry']['transref']));
	}
	echo $this->Form->create('Entry');
        echo __('Date'), ': ', $entry['Entry']['date1'];//$this->Form->hidden('id'),
        //$this->Form->input('date1', array('type'=>'text','readonly'=>TRUE));
?>
<div>
    <label for="CheckSum">Check Sum</label>
    <input readonly="readonly" maxlength="13" value="<?php echo number_format($shortfall+$this->data['Entry']['amount'],2);?>" id="CheckSum"/>
</div>
<input type="hidden" value="<?php echo $shortfall;?>" id="shortfall"/>
Code: 1XX Assets 2XX Liabilities 4XX Income 5XX Expenses
<a href='/office2/admin/accounts' target="_new">Accounts list</a>
<?php 
	echo '<input id="acc" value="';
	if ($this->data['Account']['code']) {
		echo $this->data['Account']['code'] . ':' . $this->data['Account']['name_chi'];
	}
	echo '"/>';
    echo $this->Html->scriptBlock($script1, array('inline'=>false));
    echo $this->Form->input('account_id', ['type'=>'text','id'=>'acc2', 'readonly'=>true]),
        $this->Form->input('amount');
?>
    <a href="#" id="balance">balance</a>
<?php
    echo $this->Form->input('detail'),
        $this->Form->hidden('extra1'),
        $this->Form->end(__('Update'));
	//$base = $this->request->base;
	if ($toggle)
		echo $this->Html->link('Admin edit',array('action'=>'edit',$this->data['Entry']['id'],'admin'=>true));
?>
<script>
$(function() {
	$( "#acc" ).autocomplete({
		source: function( request, response ) {
		$.ajax({
		  url: "<?=$this->base?>/accounts/autocomplete.json",
		  dataType: "json",
		  data: {
			q: request.term
		  },
		  success: function( data ) {
			response( data );
		  }
		});
	  },
	  minLength: 1,
      select: function( event, ui ) {
      	  $("#acc2").val(ui.item.id);
      }
	});
});
</script>
