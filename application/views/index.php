<div class="app_form">
	<?php echo form_open(site_uri($this->uri->uri_string())); ?>
		<p><?php echo form_label("<strong>The URL of the Shop</strong><span class='hint'>(enter it exactly like this: myshop.myshopify.com)</span>", "shop"); ?></p>
		<?php echo $this->session->flashdata("shop_error"); ?>
		<p>
			<?php echo form_input(array("name"=>"shop", "id"=>"shop", "required"=>"", "size"=>45)); ?>
			<?php echo form_submit(array("value"=>"Install")); ?>
		</p>
	<?php echo form_close(); ?>
</div>