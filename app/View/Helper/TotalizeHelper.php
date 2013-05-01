<?php
class TotalizeHelper extends AppHelper {
    public function makeAjaxCall($base, $code) {
    	$obj = "$base/entries/totalize/$code";
    	$str1 =<<<STRING
    	\$.ajax({async:true,success:function(data,textStatus){\$("#total$code").html(data);},url:
STRING;
		$obj2 = json_encode($obj);
		return "$str1 $obj2});";
    }
}
?>
