<?php 
$script1 =<<<SCRIPT
    $(function() {
    		$( "#EntryDate1" ).datepicker({ dateFormat: "yy-mm-dd",
    		onClose: function() { $("#EntryIndexForm").submit()}} );
    });
SCRIPT;
$script2 =<<<SCRIPT2
    $(document).ready(
        function() { 
          $('#link1').click(function() { 
              $('#dlg1').dialog('open'); 
              return false; 
              });
            $("#dlg1").dialog({
              dialogClass: "noshow"
            });
            $('#dlg1').dialog('close'); 
        }); 
SCRIPT2;
	echo $this->Html->css(Configure::read('Css.jquery-ui'),'stylesheet',array('inline'=>false));
	echo $this->Html->script(Configure::read('Js.jquery'), array('inline'=>false));
	echo $this->Html->script(Configure::read('Js.jquery-ui'), array('inline'=>false));
	echo $this->element('menu', array('toggle'=>$toggle));
	echo $this->Html->link(__('Add'),array('action'=>'create'));
	echo $this->Form->create('Entry'), 
		$this->Form->input('date1', array('label'=>__('Since'),'type'=>'text')),
		__('to'), "<span id='end_date'>$end_date</span>",
		"</form>";
		//$this->Form->end(__('Change'));
	echo $this->Html->scriptBlock($script1, array('inline'=>true));
        echo $this->Html->scriptBlock($script2, array('inline'=>false));
	echo $this->Paginator->numbers();
	echo $this->element('entries1', array('entries'=>$entries));
        $base = $this->request->base;
?>
<div id="dlg1" title="hey">
    <form method="get" action="<?php echo "$base/entries/create" ?>">
        <select name="type">
            <option>Cheque</option>
            <option>Cash</option>
        </select>
        <p><input type="submit" value="Create"/></p>
    </form>
</div>
