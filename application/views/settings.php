<?php
$api_key = '';
$send_email = '';
$id_templatea = '';
if(!empty($shop_setings)){
	
	$api_key = $shop_setings->api_key;
$send_email = $shop_setings->send_email;
$id_templatea = $shop_setings->id_templatea;
	
}

?>

<div class="app_table">
	<form action="" method="post">
	<input type="hidden" name="shop_name" value="<?php echo $shop; ?>">
	<?php //print_r($shop);?>
		Api key f24:
		  <input type="text" name="api_key" value="<?php echo $api_key; ?>">
		  <br>
		  
		Send Mail:
		 Yes <input type="radio" name="send_email"  <?php if($send_email==1){echo "checked";} ?> value="1"> 
		 No <input type="radio" name="send_email" <?php if($send_email==0){echo "checked";} ?> value="0"> 
		  <br>
		Id Template:
		  <input type="text" name="id_templatea" value="<?php echo $id_templatea; ?>">
		  <br><br>
		   
		  <input type="submit" value="Submit">  
	</form> 

</div>
