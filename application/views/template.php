<?php if(empty($active_tab)) $active_tab = ""; ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo ucfirst( $active_tab ) ; ?></title>
	<link href="<?php echo return_theme_path() ; ?>css/style.css" rel="stylesheet" type="text/css" />
	<script src="<?php echo return_theme_path() ; ?>js/jquery-2.2.0.min.js"></script>
	<script src="<?php echo return_theme_path() ; ?>js/dbug.script.js"></script>
</head>
<body>
<div class="form_container">
	<div class="wrapper"> 
		<div class="tab_container">
			<ul>
				<li class="<?php if($active_tab=="home") echo "active"; ?>"><a href="<?php echo site_uri("$home/home") ; ?>">Home</a></li>
				<li class="<?php if($active_tab=="settings") echo "active"; ?>"><a href="<?php echo site_uri("settings") ; ?>">Settings</a></li>
			</ul>
		</div>
		<div class="main_template" id="main_template">
			<p class="app_success"><?php if(isset($success)) echo $success; ?></p>
			<?php
			if(!empty($active_tab))
				$this->load->view( $active_tab, $this->data) ;
			//print_r($this->data) ;
			
			?>
		</div>
		<div class="clear"></div>
	</div>	
</div>
</body>
</html>