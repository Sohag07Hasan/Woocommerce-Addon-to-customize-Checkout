<?php 

/**
 * Plugin Name: Woocommerce Checkout Customization, Add fee and minimum Order
 * Author: Mahibul Hasan
 * Author Uri: http://sohag07hasan.elance.com
 * 
 * */

class WooCheckoutCustomization{
	
	function __construct(){
				
		//title changing for checkout address fields
		add_filter('woocommerce_default_address_fields', array(&$this, 'change_default_address_fields'), 100, 1);
		
		//add email place holder
		add_filter('woocommerce_billing_fields', array(&$this, 'add_placehoder'), 100);
		
		//checkout fields order
		add_filter('woocommerce_checkout_fields', array(&$this, 'woocommerce_checkout_fields'));
		
		//add a settings tab to set the minimum order
		add_filter('woocommerce_settings_tabs_array', array(&$this, 'add_new_settings_tab'), 100, 1);
		//populate the new tab
		add_action('woocommerce_settings_tabs_surcharge', array(&$this, 'populate_new_settings_tab'));
		//save the settings tabe
		add_action('woocommerce_update_options_surcharge', array(&$this, 'save_new_settings_tab'));
		
		//minimum order checking and surcharge add
		add_action('woocommerce_calculate_totals', array(&$this, 'add_surcharge_if_applicable'));
		
		//minimum order notification on cart page
		add_action('woocommerce_before_cart_table', array(&$this, 'show_cart_page_notice'));
		
	}
	
	
	//change default checkout fields
	function change_default_address_fields($fields){
		$fields['state'] = array(
				'type'              => 'state',
				'label'             => __( 'State', 'woocommerce' ),
				'placeholder'       => __( 'State', 'woocommerce' ),
				'required'          => true,
				'class'             => array( 'form-row-first', 'address-field' ),
				'custom_attributes' => array(
					'autocomplete'     => 'no'
				)
			);
		
		return $fields;
	}
	
	
	function add_placehoder($fields){
		$fields['billing_email']['placeholder'] = __('Email Address', 'woocommerce');
		return $fields;
	}
	
	
	function woocommerce_checkout_fields($fields){
		
		//var_dump($fields['order']);
		
		$fields['order']['order_comments']['placeholder'] = 'Goods are delivered via Courier and need to be signed for. If you aren’t sure if someone will be there you can give us Authority To Leave them without signature, on the understanding that responsibility for the goods passes to you, as soon as they Couriers advise delivery. If you would like the goods left, please write where to leave the order in the comments below, which will indicate acceptance of these terms. Feel free to add other order/delivery comments here too.';
				
		return $fields;
	}
	
	
	function add_new_settings_tab($tabs){
		$tabs['surcharge'] = __('Surcharge', 'woocommerce');
		return $tabs;
	}
	
	function populate_new_settings_tab(){
		include $this->get_base_directory() . 'settings-tab/surcharge.php';
	}
	
	function get_base_directory(){
		return dirname(__FILE__) . '/';
	}
	
	function save_new_settings_tab(){
		if(isset($_POST['woocommerce_minimum_order_enabled'])){
			update_option('woocommerce_minimum_order_enabled', 'yes');
		}
		else{
			update_option('woocommerce_minimum_order_enabled', 'no');
		}
		
		update_option('woocommerce_minimum_order_amount', $this->sanitize_amount($_POST['woocommerce_minimum_order_amount']));
		update_option('woocommerce_surcharge_amount', $this->sanitize_amount($_POST['woocommerce_surcharge_amount']));
	}
	
	function sanitize_amount($amount){
		return preg_replace('#[^0-9.]#', '', $amount);
	}
	
	
	function is_minimum_order_enabled(){
		$enabled = get_option('woocommerce_minimum_order_enabled');
		return $enabled == 'yes' ? true : false;
	}
	
	function get_minimum_order_amount(){
		return get_option('woocommerce_minimum_order_amount');
	}
	
	function get_surcharge_amount(){
		return get_option('woocommerce_surcharge_amount');
	}
	
	
	//add surcharge amount if applicable
	function add_surcharge_if_applicable($cart){		
		global $woocommerce;
		
		$minimum_order_amount = $this->get_minimum_order_amount();
		$min_order = $minimum_order_amount > 0 ?  $minimum_order_amount : 50;
		$cart_sub_total = $woocommerce->cart->subtotal;
		
		$surcharge = $this->get_surcharge_amount();
		$surcharge = $surcharge > 0 ? $surcharge : 5;
			
				
		if($cart_sub_total < $min_order){		
			$woocommerce->cart->add_fee( __('Surcharge', 'woocommerce'), $surcharge );
			$woocommerce->cart->cart_contents_total += $surcharge;
		}
	}
	
	
	//show minimum order notices if applicable on cart page
	function show_cart_page_notice(){
		global $woocommerce;
		
		$minimum_order_amount = $this->get_minimum_order_amount();
		$min_order = $minimum_order_amount > 0 ?  $minimum_order_amount : 50;
		$cart_sub_total = $woocommerce->cart->subtotal;
		
		$surcharge = $this->get_surcharge_amount();
		$surcharge = $surcharge > 0 ? $surcharge : 5;
		
		if($cart_sub_total < $min_order){
			?>
			<div class="woocommerce-message surcharge-notice" style="text-transform:capitalize;" > We add a surcharge ( <?php echo woocommerce_price($surcharge); ?> ) if the minimum order ( <?php echo woocommerce_price($cart_sub_total); ?> ) does not meet.</div>
			<?php 
		}
		
	}
	
}


return new WooCheckoutCustomization();


?>