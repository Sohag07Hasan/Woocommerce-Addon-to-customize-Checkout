<?php 

	$enabled = $this->is_minimum_order_enabled() ? '1' : '0';
	$order_amount = $this->get_minimum_order_amount();
	$surcharge_amount = $this->get_surcharge_amount();	

?>

<h3>Minimum Order Fee and Surcharge Management</h3>
<p>Please set up a  minimum Order fee and a Surcharge fee. If the order is below the minimu order, surcharge will be added</p>

<table class="form-table">
	<tbody>
	
		<tr valign="top">
			<th scope="row" class="titledesc"><label for="woocommerce_minimum_order_enabled">Minimum Order enabled</label></th>
			<td class="forminp forminp-checkbox">
				<fieldset>
					<legend class="screen-reader-text"><span>Minimum Order enabled</span></legend>
					<input <?php checked('1', $enabled); ?> id="woocommerce_minimum_order_enabled" name="woocommerce_minimum_order_enabled" type="checkbox" value="1" />										
				</fieldset>
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row" class="titledesc"><label for="woocommerce_minimum_order_amount">Minimum Order Amount</label></th>
			<td class="forminp forminp-checkbox">
				<fieldset>
					<legend class="screen-reader-text"><span>Minimum Order Amount (only integer) </span></legend>
					<input id="woocommerce_minimum_order_amount" name="woocommerce_minimum_order_amount" type="text" value="<?php echo $order_amount; ?>" />										
				</fieldset>
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row" class="titledesc"><label for="woocommerce_surcharge_amount">Surcharge Amount</label></th>
			<td class="forminp forminp-checkbox">
				<fieldset>
					<legend class="screen-reader-text"><span>Minimum Order enabled</span></legend>
					<input id="woocommerce_surcharge_amount" name="woocommerce_surcharge_amount" type="text" value="<?php echo $surcharge_amount; ?>" />										
				</fieldset>
			</td>
		</tr>
		
	</tbody>
</table>