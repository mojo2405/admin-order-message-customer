<?php
/**
* Plugin Name: Admin Order Message Customer
* Plugin URI: https://wordpress.org/plugins/admin-order-message-customer
* Description: Adding a link in woocommerce admin order page to contact customer via Whatsapp
* Version: 1.0
* Author: Eitan Shaked
* Author URI: https://myapp.co.il
**/


if ( !function_exists( 'admcw_woocommerce_addon_activate' ) ) {
	// Check if woocommerce is active
	function admcw_woocommerce_addon_activate() {
		if( !class_exists( 'WooCommerce' ) ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );
			wp_die( __( 'Please install and Activate WooCommerce.', 'admin-order-message-customer' ), 'Plugin dependency check', array( 'back_link' => true ) );
		}
	}
}

//sets up activation hook
register_activation_hook(__FILE__, 'admcw_woocommerce_addon_activate');

add_action( 'woocommerce_admin_order_data_after_billing_address', 'admcw_after_billing_address', 10, 3 );
if ( !function_exists( 'admcw_after_billing_address' ) ) {
	function admcw_after_billing_address( $order ){
		$phone = $order->billing_phone;
		if (!$phone)
			# Return if there is no billing phone
			return;
		# If Billing country is Israel then need to take care of country code
		if ($order->billing_country == "IL"){
			$pattern = '/^05/i';
			$replacement = '+9725';
			$phone =  preg_replace($pattern, $replacement, $phone);
		}
		
		echo '<p>
		<strong>WHATSAPP:</strong>
		<a target="_blank" href="'.esc_url("https://api.whatsapp.com/send?phone=".$phone).'">'.$phone.'</a>
		</p>
		';
	}
}