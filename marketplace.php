<?php

/**
 * Plugin Name: ShopFlix Connector for Woocommerce
 * Plugin URI: https://zonepage.gr
 * Description: This is a connector with marketplace api
 * Author: Nikos Ziozas
 * Author URI: http://www.zonepage.gr/
 * Version: 1.1.4
 * Text Domain: wc-marketplace-api
 * Domain Path: /languages/
 *
 * Copyright: (c) 2021-2022, Nikos Ziozas (nziozas@gmail.com)
 *
 * License: GNU General Public License
 * License URI: http://www.gnu.org/licenses/
 *
 * @package   ShopFlix Api
 * @author    Nikos Ziozas
 * @category  API
 * @copyright Copyright (c) 2021-2022, Nikos Ziozas
 * @license   http://www.gnu.org/licenses/ GNU General Public License
 */


/**
 * Copyright (C) 2021 Nikos Ziozas <nziozas@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
require "Connector/vendor/autoload.php";
require "settings.php";


use Onecode\ShopFlixConnector\Library\Connector;


add_action('wp_enqueue_scripts', 'shopflix_so_enqueue_scripts');
function shopflix_so_enqueue_scripts()
{
	wp_enqueue_script('ajaxHandle');
	wp_localize_script(
		'ajaxHandle',
		'ajax_object',
		array('ajaxurl' => admin_url('admin-ajax.php'))
	);
}


add_filter('manage_edit-shop_order_columns', 'Shopflix_gateway_orders_column');
function Shopflix_gateway_orders_column($columns)
{
	$new_columns = array();

	foreach ($columns as $column_key => $column_label) {
		if ('order_total' === $column_key) {
			$new_columns['Shopflix_details'] = __('Shopflix', 'woocommerce');
		}

		$new_columns[$column_key] = $column_label;
	}
	return $new_columns;
}


add_action('wp_head', 'update_shopflix_total');

function update_shopflix_total()
{
	$marketplaceapisettings_options = get_option('marketplaceapisettings_option_name');

	if (!isset($marketplaceapisettings_options['hidden_fordel_8']) || trim($marketplaceapisettings_options['hidden_fordel_8']) === '') {

		$XML_generator = new XML_generator;
		$random_url = $XML_generator->getRandomString();
		$marketplaceapisettings_options['hidden_fordel_8'] =  $random_url;
		update_option('marketplaceapisettings_option_name', $marketplaceapisettings_options);
	} else {

		$random_url = $marketplaceapisettings_options['hidden_fordel_8']; // MPN

	}
	//11435j0j
	if (isset($_GET['spf_total'])) {
		if ($_GET['spf_total'] == $random_url) {
			//require('skroutz_feded/create_xml.php');
			$orders = new get_data_local;
			echo $orders->manual_update();
			echo ("Success");
		}
	}
}




add_action('manage_shop_order_posts_custom_column', 'Shopflix_gateway_orders_column_content');
function Shopflix_gateway_orders_column_content($column)
{
	global $the_order, $post;

	// HERE below set your targeted payment method ID


	if ($column  == 'Shopflix_details') {
		$order_data = $the_order->get_meta('shopflix');
		if ($order_data === "shopflix") {
			// HERE below you will add your code and conditions
			$output = 'Παραγγελία Από Shopflix';
		} else {
			$output = '';
			//var_dump($the_order->get_shipping_method());
		}

		// Display
		echo '<div style="text-align:center;">' . $output . '</div>';
	}
}


add_action("wp_ajax_myaction", "so_wp_ajax_function");
add_action("wp_ajax_nopriv_myaction", "so_wp_ajax_function");
function so_wp_ajax_function()
{
	//DO whatever you want with data posted
	//To send back a response you have to echo the result!
	$orders = new get_data_local;
	echo $orders->get_data_orders();

	wp_die(); // ajax call must die to avoid trailing 0 in your response
}


add_action("wp_ajax_myaction_shipping", "so_wp_ajax_function_shipping");
add_action("wp_ajax_nopriv_myaction_shipping", "so_wp_ajax_function_shipping");
function so_wp_ajax_function_shipping()
{
	//DO whatever you want with data posted
	//To send back a response you have to echo the result!
	$orders = new get_data_local;
	echo $orders->get_data_shippings();

	wp_die(); // ajax call must die to avoid trailing 0 in your response
}


add_action("wp_ajax_readyship", "so_wp_ajax_readyship");
add_action("wp_ajax_nopriv_readyship", "so_wp_ajax_readyship");
function so_wp_ajax_readyship()
{
	//DO whatever you want with data posted
	//To send back a response you have to echo the result!
	$order_id = $_POST['order'];
	$orders = new get_data_local;
	echo $orders->readyship_order($order_id);

	wp_die(); // ajax call must die to avoid trailing 0 in your response
}


add_action("wp_ajax_reject", "so_wp_ajax_reject");
add_action("wp_ajax_nopriv_reject", "so_wp_ajax_reject");
function so_wp_ajax_reject()
{
	//DO whatever you want with data posted
	//To send back a response you have to echo the result!
	$order_id = $_POST['order'];
	$message = $_POST['message'];
	$orders = new get_data_local;
	echo $orders->reject_order($order_id, $message);

	wp_die(); // ajax call must die to avoid trailing 0 in your response
}


add_action("wp_ajax_accept", "so_wp_ajax_accept");
add_action("wp_ajax_nopriv_accept", "so_wp_ajax_accept");
function so_wp_ajax_accept()
{
	//DO whatever you want with data posted
	//To send back a response you have to echo the result!
	$order_id = $_POST['order'];
	$orders = new get_data_local;
	echo $orders->create_order($order_id);

	wp_die(); // ajax call must die to avoid trailing 0 in your response
}


add_action("wp_ajax_print_voucher", "so_wp_ajax_print_voucher");
add_action("wp_ajax_nopriv_print_voucher", "so_wp_ajax_print_voucher");
function so_wp_ajax_print_voucher()
{
	//DO whatever you want with data posted
	//To send back a response you have to echo the result!
	$ship_id = $_POST['order'];
	$orders = new get_data_local;
	echo $orders->voucher_print($ship_id);

	wp_die(); // ajax call must die to avoid trailing 0 in your response
}


if (!wp_next_scheduled('shopflix_xml_hourly_event_test')) {
	wp_schedule_event(time(), 'hourly', 'shopflix_xml_hourly_event_test');
}

add_action('shopflix_xml_hourly_event_test', 'shopflix_xml_do_this_hourly_test');


function shopflix_xml_do_this_hourly_test()
{

	$cron = new get_data_local();
	$cron->cron_jobs();
}


add_action('woocommerce_product_options_pricing', 'shopflix_add_RRP_to_products');

function shopflix_add_RRP_to_products($product_id)
{
	echo '<div class="form-row form-row-full" style="padding:20px; border:2px solid red; max-width: 588px;">';

	echo "<h3 style='font-size:22px; text-align:center'>ShopFlix Settings</h3>";

	global $woocommerce, $post, $product_object;
	$values =  get_post_meta($post->ID, '_enable_in_shopflix', true);
	//var_dump($values);

	woocommerce_wp_checkbox(array( // Checkbox.
		'id'            => '_enable_in_shopflix',
		'label'         => __('Enable in Shopflix', 'woocommerce'),
		'value'         => empty($values) ? 'yes' : $values,
		'description'   => __('Enable in Shopflix', 'woocommerce'),
	));

	woocommerce_wp_text_input(array(
		'id' => 'ean_shopflix',
		'class' => 'short',
		'desc_tip' => 'true',
		'description' => __('The amount of credits for this product in currency format.', 'woocommerce'),
		'label' => __('EAN', 'woocommerce'),
	));

	woocommerce_wp_text_input(array(
		'id' => 'mpn_shopflix',
		'class' => 'short',
		'label' => __('MPN', 'woocommerce'),

	));


	woocommerce_wp_text_input(array(
		'id' => 'offer_from_shopflix',
		'class' => 'sale_price_dates_from hasDatepicker',
		'desc_tip' => 'true',
		'type'        => 'date',
		'description' => __('The amount of credits for this product in currency format.', 'woocommerce'),
		'label' => __('Offer From', 'woocommerce')
	));

	woocommerce_wp_text_input(array(
		'id' => 'offer_to_shopflix',
		'class' => 'sale_price_dates_from hasDatepicker',
		'desc_tip' => 'true',
		'type'        => 'date',
		'description' => __('The amount of credits for this product in currency format.', 'woocommerce'),
		'label' => __('Offer to', 'woocommerce')
	));


	woocommerce_wp_text_input(array(
		'id' => 'offer_price_shopflix',
		'class' => 'short',
		'data_type' => 'price',
		'label' => __('Offer Price', 'woocommerce') . ' (' . get_woocommerce_currency_symbol() . ')'
	));

	woocommerce_wp_text_input(array(
		'id' => 'offer_quantity_shopflix',
		'label' => __('Offer Quantity', 'woocommerce'),
		'description' => __('Leave blank for unlimited.', 'woocommerce'),
		'type' => 'number',
		'custom_attributes' => array(
			'step' => '1',
			'min' => '0',
		),
	));


	$shipping_lead_time = get_post_meta($post->ID, 'shipping_lead_time_shopflix', true);

	if (!$shipping_lead_time) {
		$shipping_lead_time = 0;
	}

	woocommerce_wp_text_input(array(
		'id' => 'shipping_lead_time_shopflix',
		'label' => __('Shipping lead time', 'woocommerce'),

		'description' => __('Ο χρόνος που θα χρειαστείτε για την προετοιμασία του δέματος σε μέρες. *', 'woocommerce'),
		'type' => 'number',
		'custom_attributes' => array(
			'step' => '1',
			'min' => '0',
		),
	));




	echo "</div>";
}

// -----------------------------------------
// 2. Save RRP field via custom field

add_action('save_post_product', 'shopflix_save_RRP');

function shopflix_save_RRP($product_id)
{
	global $typenow;
	if ('product' === $typenow) {
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
		if (isset($_POST['enable_in_shopflix'])) {

			update_meta_data($product_id, 'enable_in_shopflix', isset($_POST['enable_in_shopflix']) ? 'yes' : 'no');
		}

		$woocommerce_checkbox = isset($_POST['_enable_in_shopflix']) ? 'yes' : 'no';
		update_post_meta($product_id, '_enable_in_shopflix', $woocommerce_checkbox);

		if (isset($_POST['ean_shopflix'])) {
			update_post_meta($product_id, 'ean_shopflix', $_POST['ean_shopflix']);
		}

		if (isset($_POST['mpn_shopflix'])) {
			update_post_meta($product_id, 'mpn_shopflix', $_POST['mpn_shopflix']);
		}

		if (isset($_POST['offer_from_shopflix'])) {
			update_post_meta($product_id, 'offer_from_shopflix', $_POST['offer_from_shopflix']);
		}
		if (isset($_POST['offer_to_shopflix'])) {
			update_post_meta($product_id, 'offer_to_shopflix', $_POST['offer_to_shopflix']);
		}

		if (isset($_POST['offer_price_shopflix'])) {
			update_post_meta($product_id, 'offer_price_shopflix', $_POST['offer_price_shopflix']);
		}

		if (isset($_POST['offer_quantity_shopflix'])) {
			update_post_meta($product_id, 'offer_quantity_shopflix', $_POST['offer_quantity_shopflix']);
		}



		if (isset($_POST['shipping_lead_time_shopflix'])) {
			update_post_meta($product_id, 'shipping_lead_time_shopflix', $_POST['shipping_lead_time_shopflix']);
		}
	}
}



add_action('woocommerce_variation_options_dimensions', 'market_place_add_custom_field_to_variations', 10, 3);

function market_place_add_custom_field_to_variations($loop, $variation_data, $variation)
{

	echo '<div class="form-row form-row-full" style="padding:20px; border:2px solid red">';

	echo "<h3 style='font-size:22px; text-align:center'>Shopflix Settings for Variation</h3>";

	global $woocommerce, $post, $product_object;
	$values =  get_post_meta($variation->ID, '_enable_in_shopflix', true);

	woocommerce_wp_checkbox(array( // Checkbox.
		'id'            => '_enable_in_shopflix[' . $loop . ']',
		'label'         => __('Enable in Shopflix', 'woocommerce'),
		'value'         => empty($values) ? 'yes' : $values,
		'description'   => __('Enable in Shopflix', 'woocommerce'),
	));


	woocommerce_wp_text_input(array(
		'id' => 'ean_shopflix[' . $loop . ']',
		'class' => 'short',
		'desc_tip' => 'true',
		'description' => __('The amount of credits for this product in currency format.', 'woocommerce'),
		'label' => __('EAN', 'woocommerce'),
		'value' => get_post_meta($variation->ID, 'ean_shopflix', true)
	));

	woocommerce_wp_text_input(array(
		'id' => 'mpn_shopflix[' . $loop . ']',
		'class' => 'short',
		'label' => __('MPN', 'woocommerce'),
		'value' => get_post_meta($variation->ID, 'mpn_shopflix', true)
	));

	woocommerce_wp_text_input(array(
		'id' => 'offer_from_shopflix[' . $loop . ']',
		'class' => 'sale_price_dates_from hasDatepicker',
		'desc_tip' => 'true',
		'type'        => 'date',
		'description' => __('The amount of credits for this product in currency format.', 'woocommerce'),
		'label' => __('Offer From', 'woocommerce'),
		'value' => get_post_meta($variation->ID, 'offer_from_shopflix', true)
	));

	woocommerce_wp_text_input(array(
		'id' => 'offer_to_shopflix[' . $loop . ']',
		'class' => 'sale_price_dates_from hasDatepicker',
		'desc_tip' => 'true',
		'type'        => 'date',
		'description' => __('The amount of credits for this product in currency format.', 'woocommerce'),
		'label' => __('Offer To', 'woocommerce'),
		'value' => get_post_meta($variation->ID, 'offer_to_shopflix', true)
	));


	woocommerce_wp_text_input(array(
		'id' => 'offer_price_shopflix[' . $loop . ']',
		'class' => 'short',
		'value' =>  get_post_meta($variation->ID, 'offer_price_shopflix', true),
		'data_type' => 'price',
		'label' => __('Offer Price', 'woocommerce') . ' (' . get_woocommerce_currency_symbol() . ')'
	));


	woocommerce_wp_text_input(array(
		'id' => 'offer_quantity_shopflix[' . $loop . ']',
		'value' => get_post_meta($variation->ID, 'offer_quantity_shopflix', true),
		'label' => __('Offer Quantity', 'woocommerce'),
		'description' => __('Leave blank for unlimited.', 'woocommerce'),
		'type' => 'number',
		'custom_attributes' => array(
			'step' => '1',
			'min' => '0',
		),
	));


	global $woocommerce, $post, $product_object;
	$shipping_lead_time = get_post_meta($variation->ID, 'shipping_lead_time_shopflix', true);

	if (!$shipping_lead_time) {
		$shipping_lead_time = 0;
	}

	woocommerce_wp_text_input(array(
		'id' => 'shipping_lead_time_shopflix[' . $loop . ']',
		'value' => $shipping_lead_time,
		'label' => __('Shipping lead time', 'woocommerce'),

		'description' => __('Ο χρόνος που θα χρειαστείτε για την προετοιμασία του δέματος σε μέρες. *', 'woocommerce'),
		'type' => 'number',
		'custom_attributes' => array(
			'step' => '1',
			'min' => '0',
		),
	));

	echo "</div>";
}



// -----------------------------------------
// 2. Save custom field on product variation save

add_action('woocommerce_save_product_variation', 'market_place_add_save_custom_field_variations', 10, 2);

function market_place_add_save_custom_field_variations($variation_id, $i)
{


	$woocommerce_checkbox = isset($_POST['_enable_in_shopflix']) ? 'yes' : 'no';
	update_post_meta($variation_id, '_enable_in_shopflix', $woocommerce_checkbox);

	$custom_field = $_POST['ean_shopflix'][$i];
	if (isset($custom_field)) update_post_meta($variation_id, 'ean_shopflix', esc_attr($custom_field));

	$mpn_field = $_POST['mpn_shopflix'][$i];
	if (isset($mpn_field)) update_post_meta($variation_id, 'mpn_shopflix', esc_attr($mpn_field));

	$offer_from = $_POST['offer_to_shopflix'][$i];
	if (isset($offer_from)) update_post_meta($variation_id, 'offer_from_shopflix', esc_attr($offer_from));

	$offer_to = $_POST['offer_to_shopflix'][$i];
	if (isset($offer_to)) update_post_meta($variation_id, 'offer_to_shopflix', esc_attr($offer_to));

	$offer_price = $_POST['offer_price'][$i];
	if (isset($offer_price)) update_post_meta($variation_id, 'offer_price_shopflix', esc_attr($offer_price));

	$offer_quantity = $_POST['offer_quantity_shopflix'][$i];
	if (isset($offer_quantity)) update_post_meta($variation_id, 'offer_quantity_shopflix', esc_attr($offer_quantity));

	$shipping_lead_time = $_POST['shipping_lead_time_shopflix'][$i];
	if (isset($shipping_lead_time)) update_post_meta($variation_id, 'shipping_lead_time_shopflix', esc_attr($shipping_lead_time));
}


// -----------------------------------------
// 3. Store custom field value into variation data

add_filter('woocommerce_available_variation', 'market_place_add_custom_field_variation_data');

function market_place_add_custom_field_variation_data($variations)
{
	$variations['ean'] = '<div class="woocommerce_custom_field">EAN: <span>' . get_post_meta($variations['variation_id'], 'ean', true) . '</span></div>';
	return $variations;
}

//add_filter('woocommerce_available_variation', 'market_place_add_mpn_variation_data');

function market_place_add_mpn_field_variation_data($variations)
{
	$variations['mpn'] = '<div class="woocommerce_custom_field">MPN: <span>' . get_post_meta($variations['variation_id'], 'mpn', true) . '</span></div>';
	return $variations;
}


class MarketPlaceApi
{

	private $market_place_api_options;


	public function __construct()
	{
		add_action('admin_menu', array($this, 'market_place_api_add_plugin_page'));
		add_action('admin_init', array($this, 'market_place_api_page_init'));
		add_action('admin_enqueue_scripts', array($this, 'cstm_css_and_js'));
		register_activation_hook(__FILE__,  array($this, 'create_plugin_database_table'));
		register_activation_hook(__FILE__,  array($this, 'register_schedule'));
		register_deactivation_hook(__FILE__, array($this, 'remove_schedule'));
		add_action('isa_add_every_three_minutes_data',  array($this, 'update_data'));
		add_filter('cron_schedules', array($this, 'my_cron_schedules'));
	}



	public function remove_schedule()
	{

		wp_clear_scheduled_hook('shopflix_xml_hourly_event_test');
		$this->remove_plugin_database_table();
	}

	public function register_schedule()
	{
	}

	public function my_cron_schedules($schedules)
	{
		if (!isset($schedules["5min"])) {
			$schedules["5min"] = array(
				'interval' => 5 * 60,
				'display' => __('Once every 5 minutes')
			);
		}
		if (!isset($schedules["30min"])) {
			$schedules["30min"] = array(
				'interval' => 30 * 60,
				'display' => __('Once every 30 minutes')
			);
		}
		return $schedules;
	}

	public function update_data()
	{

		//var_dump('test');


		require_once(ABSPATH . '/wp-admin/includes/upgrade.php');

		$marketplaceapisettings_options = get_option('marketplaceapisettings_option_name'); // Array of All Options

		if (array_key_exists('api_url_3', $marketplaceapisettings_options) && array_key_exists('password_5', $marketplaceapisettings_options) && array_key_exists('username_4', $marketplaceapisettings_options)) {

			$api_url = $marketplaceapisettings_options['api_url_3'];
			$username = $marketplaceapisettings_options['username_4'];
			$password = $marketplaceapisettings_options['password_5'];
			if (strlen($api_url) > 0 && strlen($username) > 0 && strlen($password) > 0) {






				$connector = new Connector($username, $password, $api_url);


				$data = $connector->getNewOrders();
				global $table_prefix, $wpdb;

				$tblname = 'onecode_marketplace_order';
				$wp_track_table = $table_prefix . "$tblname";
				$tblname_items = 'onecode_marketplace_order_item';
				$wp_track_table_items = $table_prefix . "$tblname_items";
				$tblname_addresses = 'onecode_marketplace_order_addresses';
				$wp_track_table_addresse = $table_prefix . "$tblname_addresses";
				if ($wpdb->get_var("show tables like '$wp_track_table'") == $wp_track_table) {

					foreach ($data as $ordder) {
						$order_exists = $wpdb->get_var(
							$wpdb->prepare("SELECT `marketplace_order_id` FROM " . $wp_track_table . "  WHERE `marketplace_order_id` = %d", $ordder['order']['shopflix_order_id'])
						);

						if ($order_exists) {
						} else {

							//print_r($ordder['order']['marketplace_order_id']);
							$sql = "INSERT INTO " . $wp_track_table . " (`marketplace_order_id`, `increment_id`, `state`, `customer_firstname`, `customer_lastname`, `subtotal`, `discount_amount`, `total_paid`, `customer_note`,  `woocommerce_orderid`) VALUES ('" . $ordder['order']['shopflix_order_id'] . "', '" . $ordder['order']['increment_id'] . "', '" . $ordder['order']['status'] . "', '" . $ordder['order']['customer_firstname'] . "', '" . $ordder['order']['customer_lastname'] . "', '" . $ordder['order']['subtotal'] . "', '" . $ordder['order']['discount_amount'] . "', '" . $ordder['order']['total_paid'] . "', '" . $ordder['order']['customer_note'] . "', '0') on duplicate key update total_paid = values(total_paid);";

							dbDelta($sql);

							$items = $ordder['items'];

							foreach ($items as $item) {

								$sql_items = "INSERT INTO " . $wp_track_table_items . " (`marketplace_order_id`, `sku`, `price`, `qnt`) VALUES ('" . $ordder['order']['shopflix_order_id'] . "','" . $item['sku'] . "', '" . $item['price'] . "', '" . $item['qty'] . "')";

								dbDelta($sql_items);
							}

							$addresses = $ordder['addresses'];

							foreach ($addresses as $addresse) {

								$sql_items = "INSERT INTO " . $wp_track_table_addresse . " (`marketplace_order_id`, `firstname`, `lastname`, `postcode`, `telephone`, `street`, `city`, `email`, `country_id`, `address_type`) VALUES ('" . $ordder['order']['shopflix_order_id'] . "','" . $addresse['firstname'] . "', '" . $addresse['lastname'] . "', '" . $addresse['postcode'] . "', '" . $addresse['telephone'] . "', '" . $addresse['street'] . "', '" . $addresse['city'] . "', '" . $addresse['email'] . "', '" . $addresse['country_id'] . "', '" . $addresse['address_type'] . "')";

								dbDelta($sql_items);
							}
						}
					}
				}
			}
		}
	}

	public function remove_plugin_database_table()
	{
		global $table_prefix, $wpdb;
		$tblname = 'onecode_marketplace_order';
		$wp_track_table = $table_prefix . "$tblname";
		$sql = "DROP TABLE IF EXISTS $wp_track_table";
		$wpdb->query($sql);
		delete_option("devnote_plugin_db_version");

		$tblname_items = 'onecode_marketplace_order_item';
		$wp_track_table_items = $table_prefix . "$tblname_items";
		$sql = "DROP TABLE IF EXISTS $wp_track_table_items";
		$wpdb->query($sql);
		delete_option("devnote_plugin_db_version");

		$tblname_addresses = 'onecode_marketplace_order_addresses';
		$wp_track_table_addresse = $table_prefix . "$tblname_addresses";
		$sql = "DROP TABLE IF EXISTS $wp_track_table_addresse";
		$wpdb->query($sql);
		delete_option("devnote_plugin_db_version");

		$tblname_shippment = 'onecode_shopflix_shippment';
		$wp_track_table_shippment = $table_prefix . "$tblname_shippment";
		$sql = "DROP TABLE IF EXISTS $wp_track_table_shippment";
		$wpdb->query($sql);
		delete_option("devnote_plugin_db_version");

		$tblname_shippment_item = 'onecode_shopflix_shippment_item';
		$wp_track_table_shippment_item = $table_prefix . "$tblname_shippment_item";
		$sql = "DROP TABLE IF EXISTS $wp_track_table_shippment_item";
		$wpdb->query($sql);
		delete_option("devnote_plugin_db_version");

		$tblname_shippment_track = 'onecode_shopflix_shippment_track';
		$wp_track_table_shippment_track = $table_prefix . "$tblname_shippment_track";
		$sql = "DROP TABLE IF EXISTS $wp_track_table_shippment_track";
		$wpdb->query($sql);
		delete_option("devnote_plugin_db_version");
	}
	public function create_plugin_database_table()
	{
		global $table_prefix, $wpdb;

		$tblname = 'onecode_marketplace_order';
		$wp_track_table = $table_prefix . "$tblname";

		#Check to see if the table exists already, if not, then create it

		if ($wpdb->get_var("show tables like '$wp_track_table'") != $wp_track_table) {

			$sql = "CREATE TABLE `" . $wp_track_table . "` ( ";
			$sql .= "  `marketplace_order_id`  int(128)   NOT NULL, ";
			$sql .= "  `increment_id`  int(128), ";
			$sql .= "  `state`  varchar(128),";
			$sql .= "  `customer_firstname`  varchar(128),";
			$sql .= "  `customer_lastname`  varchar(128),";
			$sql .= "  `subtotal`  varchar(128),";
			$sql .= "  `discount_amount`  varchar(128),";
			$sql .= "  `total_paid`  varchar(128),";
			$sql .= "  `customer_note`  varchar(128),";
			$sql .= "  `woocommerce_orderid`  int(128),";
			$sql .= "  PRIMARY KEY  (`marketplace_order_id`) ";
			$sql .= ")";
			require_once(ABSPATH . '/wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}

		$tblname_items = 'onecode_marketplace_order_item';
		$wp_track_table_items = $table_prefix . "$tblname_items";

		if ($wpdb->get_var("show tables like '$wp_track_table_items'") != $wp_track_table_items) {

			$sql = "CREATE TABLE `" . $wp_track_table_items . "` ( ";
			$sql .= "  `items_uni_id`  int(128)   NOT NULL AUTO_INCREMENT, ";
			$sql .= "  `marketplace_order_id`  int(128)   NOT NULL, ";
			$sql .= "  `sku`  varchar(128), ";
			$sql .= "  `price`  varchar(128),";
			$sql .= "  `qnt`  int(128),";
			$sql .= "  PRIMARY KEY  (`items_uni_id`) ";
			$sql .= ")";
			require_once(ABSPATH . '/wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}

		$tblname_addresses = 'onecode_marketplace_order_addresses';
		$wp_track_table_addresse = $table_prefix . "$tblname_addresses";

		if ($wpdb->get_var("show tables like '$wp_track_table_addresse'") != $wp_track_table_addresse) {

			$sql = "CREATE TABLE `" . $wp_track_table_addresse . "` ( ";
			$sql .= "  `address_uni_id`  int(128)   NOT NULL AUTO_INCREMENT, ";
			$sql .= "  `marketplace_order_id`  int(128)   NOT NULL, ";
			$sql .= "  `firstname`   varchar(128), ";
			$sql .= "  `lastname`   varchar(128), ";
			$sql .= "  `postcode`   varchar(128), ";
			$sql .= "  `telephone`   varchar(128), ";
			$sql .= "  `street`   varchar(128), ";
			$sql .= "  `city`   varchar(128), ";
			$sql .= "  `email`   varchar(128), ";
			$sql .= "  `country_id`  varchar(128), ";
			$sql .= "  `address_type`  varchar(128), ";
			$sql .= "  PRIMARY KEY  (`address_uni_id`) ";
			$sql .= ")";
			require_once(ABSPATH . '/wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}

		$tblname_shippment = 'onecode_shopflix_shippment';
		$wp_track_table_shippment = $table_prefix . "$tblname_shippment";

		if ($wpdb->get_var("show tables like '$wp_track_table_shippment'") != $wp_track_table_shippment) {

			$sql = "CREATE TABLE `" . $wp_track_table_shippment . "` ( ";
			$sql .= "  `shippment_uni_id`  int(128)   NOT NULL AUTO_INCREMENT, ";
			$sql .= "  `order_id`  int(128)   NOT NULL, ";
			$sql .= "  `firstname`   varchar(128), ";
			$sql .= "  `lastname`   varchar(128), ";
			$sql .= "  `statu`   varchar(128), ";
			$sql .= "  `shipping_id`   varchar(128), ";
			$sql .= "  PRIMARY KEY  (`shippment_uni_id`) ";
			$sql .= ")";
			require_once(ABSPATH . '/wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}

		$tblname_shippment_item = 'onecode_shopflix_shippment_item';
		$wp_track_table_shippment_item = $table_prefix . "$tblname_shippment_item";

		if ($wpdb->get_var("show tables like '$wp_track_table_shippment_item'") != $wp_track_table_shippment_item) {

			$sql = "CREATE TABLE `" . $wp_track_table_shippment_item . "` ( ";
			$sql .= "  `uni_id`  int(128)   NOT NULL AUTO_INCREMENT, ";
			$sql .= "  `parent_id`  int(128)   NOT NULL, ";
			$sql .= "  `total`   varchar(128), ";
			$sql .= "  `price`   varchar(128), ";
			$sql .= "  `qty`   varchar(128), ";
			$sql .= "  `order_item_id`   varchar(128), ";
			$sql .= "  `sku`   varchar(128), ";
			$sql .= "  `name`   varchar(128), ";
			$sql .= "  PRIMARY KEY  (`uni_id`) ";
			$sql .= ")";
			require_once(ABSPATH . '/wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}

		$tblname_shippment_track = 'onecode_shopflix_shippment_track';
		$wp_track_table_shippment_track = $table_prefix . "$tblname_shippment_track";

		if ($wpdb->get_var("show tables like '$wp_track_table_shippment_track'") != $wp_track_table_shippment_track) {

			$sql = "CREATE TABLE `" . $wp_track_table_shippment_track . "` ( ";
			$sql .= "  `uni_id`  int(128)   NOT NULL AUTO_INCREMENT, ";
			$sql .= "  `parent_id`  int(128)   NOT NULL, ";
			$sql .= "  `order_id`   varchar(128), ";
			$sql .= "  `track_number`   varchar(128), ";
			$sql .= "  `track_url`   varchar(128), ";
			$sql .= "  `order_item_id`   varchar(128), ";
			$sql .= "  PRIMARY KEY  (`uni_id`) ";
			$sql .= ")";
			require_once(ABSPATH . '/wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}
	}

	public function get_orders_admin_page()
	{

		global $table_prefix, $wpdb;

		$tblname = 'onecode_marketplace_order';
		$wp_track_table = $table_prefix . "$tblname";

		#Check to see if the table exists already, if not, then create it

		if ($wpdb->get_var("show tables like '$wp_track_table'") == $wp_track_table) {
		}
	}


	public function market_place_api_add_plugin_page()
	{
		add_menu_page(
			'ShopFlix', // page_title
			'ShopFlix', // menu_title
			'manage_options', // capability
			'market-place-api', // menu_slug
			array($this, 'market_place_api_create_admin_page'), // function
			'dashicons-money', // icon_url
			80 // position
		);
	}

	public function market_place_api_create_admin_page()
	{
		$this->market_place_api_options = get_option('market_place_api_option_name');
		require_once('xml_generator.php');


		//use 'Onecode\MarketplaceConnector\Library';

		$api = new XML_generator();
		//$bridgr = new Connector();

		//var_dump($bridgr->get_orders());
		$marketplaceapisettings_options = get_option('marketplaceapisettings_option_name'); // Array of All Options

		$var_ecom_enable = "disable";


		if (array_key_exists('enable_market_place_0', $marketplaceapisettings_options)) {
			$var_ecom_enable = "enable";
		} // Enable Market Place





		//$api->generate_xml();




?>

		<style>
			a.disable {
				cursor: not-allowed;
				opacity: 0.5;


			}

			a.details {
				font-weight: bold;
				background: #949494;
				color: white;
				font-weight: bold;
				padding: 6px 14px;
			}

			a.accept.enable {

				background: #07e842;
				color: white;
				font-weight: bold;
				padding: 6px 14px;
				text-shadow: -1px 0px 4px #929292;
			}

			a.reject.enable {

				background: #e80707;
				color: white;
				font-weight: bold;
				text-shadow: -1px 0px 4px #929292;
				padding: 6px 14px;
			}
		</style>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
		<script>
			jQuery(document).ready(function($) {




				// Some event will trigger the ajax call, you can push whatever data to the server, 
				// simply passing it to the "data" object in ajax call
				$.ajax({
					url: "/wp-admin/admin-ajax.php", // this is the object instantiated in wp_localize_script function
					type: 'POST',
					data: {
						action: 'myaction', // this is the function in your functions.php that will be triggered
						name: 'John',
						age: '38'
					},
					success: function(data) {
						//Do something with the result from server
						console.log(data);
						var json = JSON.parse(data);
						var items = '';
						var items_modals = '';

						$.each(json, function(index, item) {


							<?php if ($var_ecom_enable === "enable") { ?>
								<?php if (array_key_exists('convert_to_woocommerce_orders_1', $marketplaceapisettings_options)) { ?>


									if (item.state === "reject") {
										items += '<tr>      <th scope="row">' + item.marketplace_order_id + '</th>       <td>' + item.marketplace_order_id + '</td>       <td>' + item.state + '</td> <td>' + item.customer_firstname + '</td>       <td>' + item.customer_lastname + '</td>   <td>' + item.total_paid + '</td>  <td></td> <td></td><td><a href="<?php echo get_site_url(); ?>/wp-admin/admin.php?page=market-place-order&orderid=' + item.marketplace_order_id + '" class="details">Details</a></td></tr>';


									} else if (item.woocommerce_orderid > 0) {

										items += '<tr>      <th scope="row">' + item.marketplace_order_id + '</th>       <td>' + item.marketplace_order_id + '</td>       <td>' + item.state + '</td> <td>' + item.customer_firstname + '</td>       <td>' + item.customer_lastname + '</td>   <td>' + item.total_paid + '</td>  <td></td> <td></td><td><a href="<?php echo get_site_url(); ?>/wp-admin/admin.php?page=market-place-order&orderid=' + item.marketplace_order_id + '" class="details">Details</a></td></tr>';

									} else {
										items += '<tr>      <th scope="row">' + item.marketplace_order_id + '</th>       <td>' + item.marketplace_order_id + '</td>       <td>' + item.state + '</td> <td>' + item.customer_firstname + '</td>       <td>' + item.customer_lastname + '</td>   <td>' + item.total_paid + '</td>  <td><a class="reject <?php echo $var_ecom_enable ?>" href="#ex' + item.marketplace_order_id + '" rel="modal:open">Reject</a></td> <td><a class="accept <?php echo $var_ecom_enable ?>" href="javascript:void(0)" order="' + item.marketplace_order_id + '" onclick="sendorder(this)" >Accept</a></td><td><a href="<?php echo get_site_url(); ?>/wp-admin/admin.php?page=market-place-order&orderid=' + item.marketplace_order_id + '"  class="details">Details</a></td></tr>';
										items_modals += '<div id="ex' + item.marketplace_order_id + '" class="modal">  <h3>Είστε σίγουρος ότι θέλετε να ακυρώσετε την παραγγελία #' + item.marketplace_order_id + ';</h3> <p>Παρακαλώ πείτε μας τον λόγο:</p><textarea id="reject_reason' + item.marketplace_order_id + '" name="reject_reason" rows="4" cols="50"></textarea>  <a href="javascript:void(0)" order="' + item.marketplace_order_id + '" onclick="rejectorder(this)" class="reject' + item.marketplace_order_id + '">Ακύρωση Παραγγελίας</a></div>'

									}
								<?php     } else { ?>

									items += '<tr>      <th scope="row">' + item.marketplace_order_id + '</th>       <td>' + item.marketplace_order_id + '</td>       <td>' + item.state + '</td> <td>' + item.customer_firstname + '</td>       <td>' + item.customer_lastname + '</td>   <td>' + item.total_paid + '</td>  <td></td> <td></td><td><a href="<?php echo get_site_url(); ?>/wp-admin/admin.php?page=market-place-order&orderid=' + item.marketplace_order_id + '" class="details">Details</a></td></tr>';


							<?php    }
							} ?>
						});
						$(".table tbody").append(items);
						$(".modals").append(items_modals);


					}
				});
			});

			function sendorder(elm) {


				console.log(elm.getAttribute('order'));


				jQuery.ajax({
					url: "/wp-admin/admin-ajax.php", // this is the object instantiated in wp_localize_script function
					type: 'POST',
					data: {
						action: 'accept', // this is the function in your functions.php that will be triggered
						order: elm.getAttribute('order'),

					},
					success: function(data) {
						//Do something with the result from server
						console.log(data);
						//location.reload();


					}
				});


			}



			function rejectorder(elm) {


				message = jQuery('#reject_reason' + elm.getAttribute('order')).val()

				jQuery.ajax({
					url: "/wp-admin/admin-ajax.php", // this is the object instantiated in wp_localize_script function
					type: 'POST',
					data: {
						action: 'reject', // this is the function in your functions.php that will be triggered
						order: elm.getAttribute('order'),
						message: message,

					},
					success: function(data) {
						//Do something with the result from server
						console.log(data);
						//location.reload();


					}
				});


			}
		</script>
		<?php $cron = new get_data_local();
		$cron->update_data(); ?>

		<div class="wrap">
			<h2>ShopFlix Orders</h2>
			<p></p>
			<div class="modals"></div>
			<div class="row">
				<div class="col-md-12">
					<table class="table">
						<thead>
							<tr>
								<th scope="col">#</th>
								<th scope="col">OrderID</th>
								<th scope="col">Status</th>
								<th scope="col">First Name</th>
								<th scope="col">Last Name</th>
								<th scope="col">Total</th>
								<th scope="col">Reject</th>
								<th scope="col">Accept</th>
								<th scope="col">Details</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>

			</div>


			<p></p>
			<?php settings_errors(); ?>

			<div class="row">
				<div class="col-md-6">

				</div>

			</div>

		</div>
	<?php }



	public function cstm_css_and_js($hook)
	{

		//var_dump($hook);
		// your-slug => The slug name to refer to this menu used in "add_submenu_page"
		// tools_page => refers to Tools top menu, so it's a Tools' sub-menu page
		if ('toplevel_page_market-place-api' != $hook && 'market-place-api_page_market-place-api-settings' != $hook && 'admin_page_market-place-order' != $hook && 'shopflix_page_shopflix-shippings' != $hook) {
			return;
		}

		wp_enqueue_style('boot_css', plugins_url('css/bootstrap.css', __FILE__));
		wp_enqueue_script('boot_js', plugins_url('js/bootstrap.js', __FILE__));
	}

	public function market_place_api_page_init()
	{
		register_setting(
			'market_place_api_option_group', // option_group
			'market_place_api_option_name', // option_name
			array($this, 'market_place_api_sanitize') // sanitize_callback
		);

		add_settings_section(
			'market_place_api_setting_section', // id
			'Settings', // title
			array($this, 'market_place_api_section_info'), // callback
			'market-place-api-admin' // page
		);

		add_settings_field(
			'marketplace_0', // id
			'marketplace', // title
			array($this, 'marketplace_0_callback'), // callback
			'market-place-api-admin', // page
			'market_place_api_setting_section' // section
		);
	}

	public function market_place_api_sanitize($input)
	{
		$sanitary_values = array();
		if (isset($input['marketplace_0'])) {
			$sanitary_values['marketplace_0'] = sanitize_text_field($input['marketplace_0']);
		}

		return $sanitary_values;
	}

	public function market_place_api_section_info()
	{
	}

	public function marketplace_0_callback()
	{
		printf(
			'<input class="regular-text" type="text" name="market_place_api_option_name[marketplace_0]" id="marketplace_0" value="%s">',
			isset($this->market_place_api_options['marketplace_0']) ? esc_attr($this->market_place_api_options['marketplace_0']) : ''
		);
	}
}
if (is_admin())
	$market_place_api = new MarketPlaceApi();

/* 
 * Retrieve this value with:
 * $market_place_api_options = get_option( 'market_place_api_option_name' ); // Array of All Options
 * $marketplace_0 = $market_place_api_options['marketplace_0']; // marketplace
 */



class orders
{

	public function __construct()
	{
		add_action('admin_menu', array($this, 'orders_plugin_page'));
	}

	public function orders_plugin_page()
	{
		add_submenu_page(
			null,

			'Market Place Order', // page_title
			'Market Place Order', // menu_title
			'manage_options', // capability
			'market-place-order', // menu_slug
			array($this, 'marketplaceapisettings_create_admin_page') // function
		);
	}

	private function get_product_by_sku($sku)
	{

		global $wpdb;

		$product_id = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku));

		//if ($product_id) return new WC_Product($product_id);

		return $product_id;
	}

	private function wc_get_product_id_by_variation_sku($sku)
	{
		$args = array(
			'post_type'  => 'product_variation',
			'meta_query' => array(
				array(
					'key'   => '_sku',
					'value' => $sku,
				)
			)
		);
		// Get the posts for the sku
		$posts = get_posts($args);
		if ($posts) {
			return $posts[0]->post_parent;
		} else {
			return false;
		}
	}

	public function marketplaceapisettings_create_admin_page()
	{
		$order = $_GET['orderid'];

		global $table_prefix, $wpdb;
		$tblname = 'onecode_marketplace_order';
		$wp_track_table = $table_prefix . "$tblname";
		$tblname_items = 'onecode_marketplace_order_item';
		$wp_track_table_items = $table_prefix . "$tblname_items";
		$sql = "SELECT * FROM " . $wp_track_table . " WHERE marketplace_order_id=" . $order;
		$post_id = $wpdb->get_results($sql);
		$sql_items = "SELECT * FROM " . $wp_track_table_items . " WHERE marketplace_order_id=" . $order;
		$post_id_items = $wpdb->get_results($sql_items);
		$tblname_addresses = 'onecode_marketplace_order_addresses';
		$wp_track_table_addresse = $table_prefix . "$tblname_addresses";
		$sql_addresse = "SELECT * FROM " . $wp_track_table_addresse . " WHERE marketplace_order_id=" . $order;
		$post_id_addresse = $wpdb->get_results($sql_addresse);

	?>

		<div class="wrap">
			<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
			<style>
				a.disable {
					cursor: not-allowed;
					opacity: 0.5;


				}

				a.details {
					font-weight: bold;
					background: #949494;
					color: white;
					font-weight: bold;
					padding: 6px 14px;
				}

				a.accept {

					background: #07e842;
					margin: 10px;
					color: white;
					font-weight: bold;
					padding: 6px 14px;
					text-shadow: -1px 0px 4px #929292;
				}

				a.reject {
					margin: 10px;
					background: #e80707;
					color: white;
					font-weight: bold;
					text-shadow: -1px 0px 4px #929292;
					padding: 6px 14px;
				}
			</style>
			<script>
				function sendorder(elm) {


					console.log(elm.getAttribute('order'));


					jQuery.ajax({
						url: "/wp-admin/admin-ajax.php", // this is the object instantiated in wp_localize_script function
						type: 'POST',
						data: {
							action: 'accept', // this is the function in your functions.php that will be triggered
							order: elm.getAttribute('order'),

						},
						success: function(data) {
							//Do something with the result from server
							console.log(data);
							location.reload();


						}
					});


				}


				function readyship(elm) {


					console.log(elm.getAttribute('order'));


					jQuery.ajax({
						url: "/wp-admin/admin-ajax.php", // this is the object instantiated in wp_localize_script function
						type: 'POST',
						data: {
							action: 'readyship', // this is the function in your functions.php that will be triggered
							order: elm.getAttribute('order'),

						},
						success: function(data) {
							//Do something with the result from server
							console.log(data);
							location.reload();


						}
					});


				}



				function rejectorder(elm) {


					message = jQuery('#reject_reason' + elm.getAttribute('order')).val()

					jQuery.ajax({
						url: "/wp-admin/admin-ajax.php", // this is the object instantiated in wp_localize_script function
						type: 'POST',
						data: {
							action: 'reject', // this is the function in your functions.php that will be triggered
							order: elm.getAttribute('order'),
							message: message,

						},
						success: function(data) {
							//Do something with the result from server
							console.log(data);
							location.reload();


						}
					});


				}
			</script>
			<h2 style="margin-top: 40px;">Παραγγελία ShopFlix # <?php echo $order ?></h2>
			<div class="actions" style="margin-top: 40px;">
				<?php
				$marketplaceapisettings_options = get_option('marketplaceapisettings_option_name'); // Array of All Options
				if (array_key_exists('convert_to_woocommerce_orders_1', $marketplaceapisettings_options)) {


					if ($post_id[0]->state === "reject") {
						echo "";
					}

					if ($post_id[0]->woocommerce_orderid > 0 && $post_id[0]->state != "ready to ship") {

						echo '<a class="accept" href="javascript:void(0)" order="' . $post_id[0]->marketplace_order_id . '"  onclick="readyship(this)" >Έτοιμη Προς Παραλαβή</a>';
					} else if ($post_id[0]->state === "ready to ship") {
					} else {

						echo '<a class="accept" href="javascript:void(0)" order="' . $post_id[0]->marketplace_order_id . '"  onclick="sendorder(this)" >Αποδοχή</a>';
						echo '<a class="reject" href="#ex' . $post_id[0]->marketplace_order_id . '" rel="modal:open">Reject</a>';
						echo '<div id="ex' . $post_id[0]->marketplace_order_id . '" class="modal">  <h3>Είστε σίγουρος ότι θέλετε να ακυρώσετε την παραγγελία #'  . $post_id[0]->marketplace_order_id .  ';</h3> <p>Παρακαλώ πείτε μας τον λόγο:</p><textarea id="reject_reason' . $post_id[0]->marketplace_order_id . '" name="reject_reason" rows="4" cols="50"></textarea>  <a href="javascript:void(0)" order="' . $post_id[0]->marketplace_order_id . '" onclick="rejectorder(this)" class="reject' . $post_id[0]->marketplace_order_id . '">Ακύρωση Παραγγελίας</a></div>';
					}
				}

				?>
			</div>
			<div class="row" style="margin-top: 40px;">

				<div class="col-md-4">
					<h3 style="margin-bottom:30px;">Στοιχεία Παραγγελίας #<?php echo $post_id[0]->marketplace_order_id ?></h3>

					<ul class="list-group list-group-flush">
						<li class="list-group-item">Market Place Orderid: <strong><?php echo $post_id[0]->marketplace_order_id ?></strong></li>
						<li class="list-group-item">Status: <strong><?php echo $post_id[0]->state ?></strong></li>
						<li class="list-group-item">First Name : <strong><?php echo $post_id[0]->customer_firstname ?></strong></li>
						<li class="list-group-item">Last Name : <strong><?php echo $post_id[0]->customer_lastname ?></strong></li>
						<li class="list-group-item">Sub Total : <strong><?php echo $post_id[0]->subtotal ?> €</strong></li>
						<li class="list-group-item">Total : <strong><?php echo $post_id[0]->total_paid ?> €</strong></li>
						<li class="list-group-item">Discount : <strong><?php echo $post_id[0]->discount_amount ?> €</strong></li>
						<li class="list-group-item">Customer note : <strong><?php echo $post_id[0]->customer_note ?></strong></li>
						<?php if ($post_id[0]->woocommerce_orderid == '0') { ?>
							<li class="list-group-item">Η παραγγελία δεν έχει μεταφερθεί στο Woocommerce</li>
						<?php } else { ?>
							<li class="list-group-item">Woocommerce Id : #<strong><?php echo $post_id[0]->woocommerce_orderid ?></strong></li>
						<?php } ?>


					</ul>





				</div>
				<div class="col-md-4">


					<h3 style="margin-bottom:30px;">Στοιχεία Αποστολής</h3>

					<ul class="list-group list-group-flush">

						<li class="list-group-item">First Name : <strong><?php echo $post_id_addresse[0]->firstname ?></strong></li>
						<li class="list-group-item">Last Name : <strong><?php echo $post_id_addresse[0]->lastname ?></strong></li>
						<li class="list-group-item">Τηλέφωνο : <strong><?php echo  $post_id_addresse[0]->telephone ?></strong></li>
						<li class="list-group-item">Email : <strong><?php echo  $post_id_addresse[0]->email ?></strong></li>
						<li class="list-group-item">Διεύθυνση : <strong><?php echo  $post_id_addresse[0]->street ?></strong></li>
						<li class="list-group-item">Πόλη : <strong><?php echo  $post_id_addresse[0]->city ?></strong></li>
						<li class="list-group-item">TK : <strong><?php echo  $post_id_addresse[0]->postcode ?></strong></li>
						<li class="list-group-item">Χώρα : <strong><?php echo  $post_id_addresse[0]->country_id ?></strong></li>


					</ul>




				</div>
				<div class="col-md-4">


					<h3 style="margin-bottom:30px;">Στοιχεία Χρέωσης</h3>

					<ul class="list-group list-group-flush">

						<li class="list-group-item">First Name : <strong><?php echo $post_id_addresse[1]->firstname ?></strong></li>
						<li class="list-group-item">Last Name : <strong><?php echo $post_id_addresse[1]->lastname ?></strong></li>
						<li class="list-group-item">Τηλέφωνο : <strong><?php echo  $post_id_addresse[1]->telephone ?></strong></li>
						<li class="list-group-item">Email : <strong><?php echo  $post_id_addresse[1]->email ?></strong></li>
						<li class="list-group-item">Διεύθυνση : <strong><?php echo  $post_id_addresse[1]->street ?></strong></li>
						<li class="list-group-item">Πόλη : <strong><?php echo  $post_id_addresse[1]->city ?></strong></li>
						<li class="list-group-item">TK : <strong><?php echo  $post_id_addresse[1]->postcode ?></strong></li>
						<li class="list-group-item">Χώρα : <strong><?php echo  $post_id_addresse[1]->country_id ?></strong></li>


					</ul>

				</div>

				<div class="col-md-12">
					<h3 style="margin-bottom:30px; margin-top:30px;">Προϊόντα</h3>

					<table class="table">
						<thead>
							<tr>
								<th scope="col">#</th>
								<th scope="col">sku</th>
								<th scope="col"></th>
								<th scope="col">Όνομα</th>
								<th scope="col">Ποσότητα</th>
								<th scope="col">Τιμή</th>

							</tr>
						</thead>
						<tbody>
							<?php
							$i = 1;
							foreach ($post_id_items as $post_id_item) {
								//var_dump($post_id_item->sku);
								$product_id = $this->get_product_by_sku($post_id_item->sku);


								//$product_id = $this->wc_get_product_id_by_variation_sku($post_id_item->sku);



								$product = wc_get_product($product_id);
								$product = wc_get_product($product);
								//var_dump($product->get_parent_id());
								if ($product->post_type === "product_variation") {

									$protitle = $product->get_formatted_name();
								} else {


									$protitle = $product->get_title();
								}
								//var_dump($product_0);
								//var_dump($product->post_type);



								//$attachment_url = wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'medium')[0];

							?>

								<tr>
									<th scope="row"><?php echo $i ?></th>
									<td><?php echo $post_id_item->sku ?></td>
									<td><img style="width:50px" src="<?php echo wp_get_attachment_url($product->get_image_id()); ?>"></td>
									<td><?php echo $protitle ?></td>
									<td><?php echo $post_id_item->qnt ?></td>
									<td><?php echo $post_id_item->price ?></td>
								</tr>

							<?php $i++;
							} ?>
						</tbody>
					</table>

				</div>

			</div>



		</div>
	<?php }
}
if (is_admin())
	$marketplaceapi = new orders();


class MarketPlaceApiSettings
{
	private $marketplaceapisettings_options;

	public function __construct()
	{
		add_action('admin_menu', array($this, 'market_place_api_settings_add_plugin_page'));
		add_action('admin_init', array($this, 'marketplaceapisettings_page_init'));
	}

	public function market_place_api_settings_add_plugin_page()
	{
		add_submenu_page(
			'market-place-api',
			'ShopFlix Api Settings', // page_title
			'ShopFlix Api Settings', // menu_title
			'manage_options', // capability
			'market-place-api-settings', // menu_slug
			array($this, 'marketplaceapisettings_create_admin_page') // function
		);
	}


	public function marketplaceapisettings_create_admin_page()
	{
		$cron = new get_data_local();
		$cron->cron_jobs();
		$this->marketplaceapisettings_options = get_option('marketplaceapisettings_option_name'); ?>

		<div class="wrap">
			<h2>ShopFlix Api Settings</h2>
			<p></p>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
				settings_fields('marketplaceapisettings_option_group');
				do_settings_sections('marketplaceapisettings-admin');
				submit_button();
				?>
			</form>
		</div>
	<?php }

	public function marketplaceapisettings_page_init()
	{
		register_setting(
			'marketplaceapisettings_option_group', // option_group
			'marketplaceapisettings_option_name', // option_name
			array($this, 'marketplaceapisettings_sanitize') // sanitize_callback
		);

		add_settings_section(
			'marketplaceapisettings_setting_section', // id
			'Settings', // title
			array($this, 'marketplaceapisettings_section_info'), // callback
			'marketplaceapisettings-admin' // page
		);

		add_settings_field(
			'enable_market_place_0', // id
			'Enable ShopFlix', // title
			array($this, 'enable_market_place_0_callback'), // callback
			'marketplaceapisettings-admin', // page
			'marketplaceapisettings_setting_section' // section
		);

		add_settings_field(
			'convert_to_woocommerce_orders_1', // id
			'Convert To Woocommerce Orders', // title
			array($this, 'convert_to_woocommerce_orders_1_callback'), // callback
			'marketplaceapisettings-admin', // page
			'marketplaceapisettings_setting_section' // section
		);

		add_settings_field(
			'auto_convert_to_woocommerce_orders_2', // id
			'Auto Convert To Woocommerce Orders', // title
			array($this, 'auto_convert_to_woocommerce_orders_2_callback'), // callback
			'marketplaceapisettings-admin', // page
			'marketplaceapisettings_setting_section' // section
		);




		add_settings_field(
			'api_url_3', // id
			'Api Url', // title
			array($this, 'api_url_3_callback'), // callback
			'marketplaceapisettings-admin', // page
			'marketplaceapisettings_setting_section' // section
		);

		add_settings_field(
			'username_4', // id
			'Username', // title
			array($this, 'username_4_callback'), // callback
			'marketplaceapisettings-admin', // page
			'marketplaceapisettings_setting_section' // section
		);

		add_settings_field(
			'password_5', // id
			'API KEY', // title
			array($this, 'password_5_callback'), // callback
			'marketplaceapisettings-admin', // page
			'marketplaceapisettings_setting_section' // section
		);



		add_settings_field(
			'generate_xml_6', // id
			'Generate XML', // title
			array($this, 'generate_xml_6_callback'), // callback
			'marketplaceapisettings-admin', // page
			'marketplaceapisettings_setting_section' // section
		);



		add_settings_field(
			'wordpress_cron_13', // id
			'Wordpress Cron', // title
			array($this, 'wordpress_cron_13_callback'), // callback
			'marketplaceapisettings-admin', // page
			'marketplaceapisettings_setting_section' // section
		);





		add_settings_field(
			'mpn_7', // id
			'MPN', // title
			array($this, 'mpn_7_callback'), // callback
			'marketplaceapisettings-admin', // page
			'marketplaceapisettings_setting_section' // section
		);

		add_settings_field(
			'barcode_9', // id
			'EAN', // title
			array($this, 'barcode_9_callback'), // callback
			'marketplaceapisettings-admin', // page
			'marketplaceapisettings_setting_section' // section
		);

		add_settings_field(
			'manufacturer_10', // id
			'Manufacturer', // title
			array($this, 'manufacturer_10_callback'), // callback
			'marketplaceapisettings-admin', // page
			'marketplaceapisettings_setting_section' // section
		);

		add_settings_field(
			'hidden_fordel_8', // id
			'XML αρχεία', // title
			array($this, 'hidden_fordel_8_callback'), // callback
			'marketplaceapisettings-admin', // page
			'marketplaceapisettings_setting_section' // section
		);


		add_settings_field(
			'print_19', // id
			'Select Voucher Print', // title
			array($this, 'print_19_callback'), // callback
			'marketplaceapisettings-admin', // page
			'marketplaceapisettings_setting_section' // section
		);
	}

	public function marketplaceapisettings_sanitize($input)
	{
		$sanitary_values = array();
		if (isset($input['enable_market_place_0'])) {
			$sanitary_values['enable_market_place_0'] = $input['enable_market_place_0'];
		}

		if (isset($input['convert_to_woocommerce_orders_1'])) {
			$sanitary_values['convert_to_woocommerce_orders_1'] = $input['convert_to_woocommerce_orders_1'];
		}

		if (isset($input['auto_convert_to_woocommerce_orders_2'])) {
			$sanitary_values['auto_convert_to_woocommerce_orders_2'] = $input['auto_convert_to_woocommerce_orders_2'];
		}

		if (isset($input['add_shopflix_fields_in_products_11'])) {
			$sanitary_values['add_shopflix_fields_in_products_11'] = $input['add_shopflix_fields_in_products_11'];
		}

		if (isset($input['api_url_3'])) {
			$sanitary_values['api_url_3'] = sanitize_text_field($input['api_url_3']);
		}

		if (isset($input['username_4'])) {
			$sanitary_values['username_4'] = sanitize_text_field($input['username_4']);
		}

		if (isset($input['password_5'])) {
			$sanitary_values['password_5'] = sanitize_text_field($input['password_5']);
		}

		if (isset($input['generate_xml_6'])) {
			$sanitary_values['generate_xml_6'] = $input['generate_xml_6'];
		}

		if (isset($input['wordpress_cron_13'])) {
			$sanitary_values['wordpress_cron_13'] = $input['wordpress_cron_13'];
		}




		if (isset($input['mpn_7'])) {
			$sanitary_values['mpn_7'] = $input['mpn_7'];
		}

		if (isset($input['barcode_9'])) {
			$sanitary_values['barcode_9'] = $input['barcode_9'];
		}

		if (isset($input['print_19'])) {
			$sanitary_values['print_19'] = $input['print_19'];
		}

		if (isset($input['manufacturer_10'])) {
			$sanitary_values['manufacturer_10'] = $input['manufacturer_10'];
		}


		if (isset($input['hidden_fordel_8'])) {
			$sanitary_values['hidden_fordel_8'] = $input['hidden_fordel_8'];
		}

		return $sanitary_values;
	}

	public function marketplaceapisettings_section_info()
	{
	}

	public function enable_market_place_0_callback()
	{
		printf(
			'<input type="checkbox" name="marketplaceapisettings_option_name[enable_market_place_0]" id="enable_market_place_0" value="enable_market_place_0" %s> <label for="enable_market_place_0">Ενεργοποίηση ShopFlix</label>',
			(isset($this->marketplaceapisettings_options['enable_market_place_0']) && $this->marketplaceapisettings_options['enable_market_place_0'] === 'enable_market_place_0') ? 'checked' : ''
		);
	}

	public function convert_to_woocommerce_orders_1_callback()
	{
		printf(
			'<input type="checkbox" name="marketplaceapisettings_option_name[convert_to_woocommerce_orders_1]" id="convert_to_woocommerce_orders_1" value="convert_to_woocommerce_orders_1" %s> <label for="convert_to_woocommerce_orders_1">Μεταφορά νέων παραγγελιών στο Woocommerce</label>',
			(isset($this->marketplaceapisettings_options['convert_to_woocommerce_orders_1']) && $this->marketplaceapisettings_options['convert_to_woocommerce_orders_1'] === 'convert_to_woocommerce_orders_1') ? 'checked' : ''
		);
	}

	public function auto_convert_to_woocommerce_orders_2_callback()
	{
		printf(
			'<input type="checkbox" name="marketplaceapisettings_option_name[auto_convert_to_woocommerce_orders_2]" id="auto_convert_to_woocommerce_orders_2" value="auto_convert_to_woocommerce_orders_2" %s> <label for="auto_convert_to_woocommerce_orders_2">Αυτόματη μεταφορά νέων παραγγελιών στο Woocommerce</label>',
			(isset($this->marketplaceapisettings_options['auto_convert_to_woocommerce_orders_2']) && $this->marketplaceapisettings_options['auto_convert_to_woocommerce_orders_2'] === 'auto_convert_to_woocommerce_orders_2') ? 'checked' : ''
		);
	}








	public function api_url_3_callback()
	{
		printf(
			'<input class="regular-text" type="text" name="marketplaceapisettings_option_name[api_url_3]" id="api_url_3" value="%s">',
			isset($this->marketplaceapisettings_options['api_url_3']) ? esc_attr($this->marketplaceapisettings_options['api_url_3']) : ''
		);
	}

	public function username_4_callback()
	{
		printf(
			'<input class="regular-text" type="text" name="marketplaceapisettings_option_name[username_4]" id="username_4" value="%s">',
			isset($this->marketplaceapisettings_options['username_4']) ? esc_attr($this->marketplaceapisettings_options['username_4']) : ''
		);
	}

	public function password_5_callback()
	{
		printf(
			'<input class="regular-text" type="password" name="marketplaceapisettings_option_name[password_5]" id="password_5" value="%s">',
			isset($this->marketplaceapisettings_options['password_5']) ? esc_attr($this->marketplaceapisettings_options['password_5']) : ''
		);
	}


	public function wordpress_cron_13_callback()
	{
		$marketplaceapisettings_options = get_option('marketplaceapisettings_option_name');
		$random_url = $marketplaceapisettings_options['hidden_fordel_8']; // MPN

		printf(
			'Προσοχή! Ορισμένες εταιρίες Hosting έχουν κλειστό τον Cron.<br>' .
				'<br>Έαν δεν ενεργοποιήσετε τον Cron του Wordpress μπορείτε να ορίσετε χειροκίνητα <br>από τον Server τον Cron να καλεί το <strong>' . get_site_url() . '/?spf_total=' . $random_url . ' </strong>κάθε 1 ώρα.<br><br>' .
				'<input type="checkbox" name="marketplaceapisettings_option_name[wordpress_cron_13]" id="wordpress_cron_13" value="wordpress_cron_13" %s> <label for="wordpress_cron_13">Ενεργοποίηση Wordpress Cron</label>',
			(isset($this->marketplaceapisettings_options['wordpress_cron_13']) && $this->marketplaceapisettings_options['wordpress_cron_13'] === 'wordpress_cron_13') ? 'checked' : ''
		);
	}





	public function generate_xml_6_callback()
	{

		printf(
			'<input type="checkbox" name="marketplaceapisettings_option_name[generate_xml_6]" id="generate_xml_6" value="generate_xml_6" %s> <label for="generate_xml_6">Ενεργοποίηση XML Feed</label>',
			(isset($this->marketplaceapisettings_options['generate_xml_6']) && $this->marketplaceapisettings_options['generate_xml_6'] === 'generate_xml_6') ? 'checked' : ''
		);
	}


	public function hidden_fordel_8_callback()
	{
		printf(
			'<input class="regular-text" style="display:none;" type="text" name="marketplaceapisettings_option_name[hidden_fordel_8]" id="hidden_fordel_8" value="%s">',
			isset($this->marketplaceapisettings_options['hidden_fordel_8']) ? esc_attr($this->marketplaceapisettings_options['hidden_fordel_8']) : ''
		);

		$marketplaceapisettings_options = get_option('marketplaceapisettings_option_name');

		if (!isset($marketplaceapisettings_options['hidden_fordel_8']) || trim($marketplaceapisettings_options['hidden_fordel_8']) === '') {

			printf('Δεν έχει δημιουργηθεί ακόμη το XML. Παρακαλούμε πατήστε <a href="#" onclick="createxml()">Εδώ για να δημιουργηθεί πρώτη φορά.</a>');
		} else {

			printf('<a href="/wp-content/uploads/' . $marketplaceapisettings_options['hidden_fordel_8'] . '/feed.xml" target="_blank">Link για xml</a>');
			printf('<br><a href="/wp-content/uploads/' . $marketplaceapisettings_options['hidden_fordel_8'] . '/feed.xml" target="_blank">Link για xml Simple</a>');
		}
	}

	public function mpn_7_callback()
	{
		$meta_keys = get_meta_keys();
		$custom_mpn = $this->marketplaceapisettings_options['mpn_7'];



		echo '<select  name="marketplaceapisettings_option_name[mpn_7]" id="mpn_7">';
		echo "<option value='0'>" . __('-Default-', 'shopflix-woocommerce-feed') . "</option>";

		foreach ($meta_keys as $key => $metaKey) {
			$selected = false;
			if ($custom_mpn == $metaKey) {
				$selected = true;
			}

			echo "<option value='" . esc_html($metaKey) . "' " . selected($selected, true, false) . ">" . esc_html($metaKey) . "</option>";
		}
		echo '</select>';


	?> <?php
	}

	public function barcode_9_callback()
	{
		$meta_keys = get_meta_keys();
		if (isset($this->marketplaceapisettings_options['barcode_9'])) {
			$custom_mpn = $this->marketplaceapisettings_options['barcode_9'];
		}



		echo '<select  name="marketplaceapisettings_option_name[barcode_9]" id="barcode_9">';
		echo "<option value='0'>" . __('-Default-', 'shopflix-woocommerce-feed') . "</option>";

		foreach ($meta_keys as $key => $metaKey) {
			$selected = false;
			if ($custom_mpn == $metaKey) {
				$selected = true;
			}

			echo "<option value='" . esc_html($metaKey) . "' " . selected($selected, true, false) . ">" . esc_html($metaKey) . "</option>";
		}
		echo '</select>';


		?> <?php
		}

		public function print_19_callback()
		{
			$meta_keys = get_meta_keys();
			if (isset($this->marketplaceapisettings_options['print_19'])) {
				$custom_mpn = $this->marketplaceapisettings_options['print_19'];
			}



			echo '<select  name="marketplaceapisettings_option_name[print_19]" id="print_19">';
			echo "<option value='pdf'>Courier Center Labeled</option>";
			echo "<option value='clean'>Courier Center Standard</option>";
			echo "<option value='singleclean'>Courier Center Standard ( 1 tracking voucher per page )</option>";
			echo "<option value='singlepdf'>Courier Center Labeled ( 1 tracking voucher per page )</option>";
			echo "<option value='singlepdf_100x150'>SHOPFLIX Labeled 100x150</option>";
			echo "<option value='singlepdf_100x170'>SHOPFLIX Labeled 100x170</option>";
			echo '</select>';


			?> <?php
			}


			public function manufacturer_10_callback()
			{

				$meta_keys = get_meta_keys();
				if (isset($this->marketplaceapisettings_options['manufacturer_10'])) {
					$custom_mpn = $this->marketplaceapisettings_options['manufacturer_10'];
				}



				echo '<select  name="marketplaceapisettings_option_name[manufacturer_10]" id="manufacturer_10">';
				echo "<option value='0'>" . __('-Default-', 'shopflix-woocommerce-feed') . "</option>";

				foreach ($meta_keys as $key => $metaKey) {
					$selected = false;
					if ($custom_mpn == $metaKey) {
						$selected = true;
					}

					echo "<option value='" . esc_html($metaKey) . "' " . selected($selected, true, false) . ">" . esc_html($metaKey) . "</option>";
				}
				echo '</select>';


				?> <?php
				}
			}
			if (is_admin())
				$marketplaceapisettings = new MarketPlaceApiSettings();

			/* 
 * Retrieve this value with:
 * $marketplaceapisettings_options = get_option( 'marketplaceapisettings_option_name' ); // Array of All Options
 * $enable_market_place_0 = $marketplaceapisettings_options['enable_market_place_0']; // Enable Market Place
 * $convert_to_woocommerce_orders_1 = $marketplaceapisettings_options['convert_to_woocommerce_orders_1']; // Convert To Woocommerce Orders
 * $auto_convert_to_woocommerce_orders_2 = $marketplaceapisettings_options['auto_convert_to_woocommerce_orders_2']; // Auto Convert To Woocommerce Orders
 * $api_url_3 = $marketplaceapisettings_options['api_url_3']; // Api Url
 * $username_4 = $marketplaceapisettings_options['username_4']; // Username
 * $password_5 = $marketplaceapisettings_options['password_5']; // Password
 * $generate_xml_6 = $marketplaceapisettings_options['generate_xml_6']; // Generate XML
 * $mpn_7 = $marketplaceapisettings_options['mpn_7']; // MPN
 */


			class Shopflix_shiiping
			{
				private $marketplaceapisettings_options;



				public function __construct()
				{
					add_action('admin_menu', array($this, 'market_place_api_shopping_add_plugin_page'));
					//add_action('admin_enqueue_scripts', array($this, 'cstm_css_and_js'));
					//add_action('admin_init', array($this, 'marketplaceapishoppings_page_init'));
				}



				public function market_place_api_shopping_add_plugin_page()
				{
					add_submenu_page(
						'market-place-api',
						'ShopFlix Shipping', // page_title
						'ShopFlix Shippings', // menu_title
						'manage_options', // capability
						'shopflix-shippings', // menu_slug
						array($this, 'marketplaceapishippings_create_admin_page') // function
					);
				}

				public function marketplaceapishippings_create_admin_page()
				{

					$marketplaceapisettings_options = get_option('marketplaceapisettings_option_name'); // Array of All Options

					$var_ecom_enable = "disable";


					if (array_key_exists('enable_market_place_0', $marketplaceapisettings_options)) {
						$var_ecom_enable = "enable";
					} // Enable Market Place





					//$api->generate_xml();




					?>

		<style>
			a.disable {
				cursor: not-allowed;
				opacity: 0.5;


			}

			a.details {
				font-weight: bold;
				background: #949494;
				color: white;
				font-weight: bold;
				padding: 6px 14px;
			}

			a.accept.enable {

				background: #07e842;
				color: white;
				font-weight: bold;
				padding: 6px 14px;
				text-shadow: -1px 0px 4px #929292;
			}

			a.reject.enable {

				background: #e80707;
				color: white;
				font-weight: bold;
				text-shadow: -1px 0px 4px #929292;
				padding: 6px 14px;
			}
		</style>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
		<script>
			jQuery(document).ready(function($) {




				// Some event will trigger the ajax call, you can push whatever data to the server, 
				// simply passing it to the "data" object in ajax call
				$.ajax({
					url: "/wp-admin/admin-ajax.php", // this is the object instantiated in wp_localize_script function
					type: 'POST',
					data: {
						action: 'myaction_shipping', // this is the function in your functions.php that will be triggered
						name: 'John',
						age: '38'
					},
					success: function(data) {
						//Do something with the result from server
						console.log(data);
						var json = JSON.parse(data);
						var items = '';
						var items_modals = '';

						$.each(json, function(index, item) {


							<?php if ($var_ecom_enable === "enable") { ?>


								if (item.track_number === "") {
									items += '<tr>      <th scope="row">' + item.shippment_uni_id + '</th>       <td>' + item.order_id + '</td>       <td>' + item.shipping_id + '</td> <td>' + item.firstname + '</td>       <td>' + item.lastname + '</td>   <td>' + item.statu + '</td>  <td>' + item.track_number + '</td> <td><a href="' + item.track_url + '" target="_blank">Tracking Link</a></td><td><a class="accept <?php echo $var_ecom_enable ?>" href="javascript:void(0)" order="' + item.shipping_id + '" onclick="printvpucher(this)" >Print Voucher</a></td></tr>';


								} else {

									items += '<tr>      <th scope="row">' + item.shippment_uni_id + '</th>       <td>' + item.order_id + '</td>       <td>' + item.shipping_id + '</td> <td>' + item.firstname + '</td>       <td>' + item.lastname + '</td>   <td>' + item.statu + '</td>  <td>' + item.track_number + '</td> <td><a href="' + item.track_url + '" target="_blank">Tracking Link</a></td><td><a class="accept <?php echo $var_ecom_enable ?>" href="javascript:void(0)" order="' + item.shipping_id + '" onclick="printvpucher(this)" >Print Voucher</a></td></tr>';

								}
							<?php
							} ?>
						});
						$(".table tbody").append(items);
						$(".modals").append(items_modals);


					}
				});
			});

			function printvpucher(elm) {


				console.log(elm.getAttribute('order'));


				jQuery.ajax({
					url: "/wp-admin/admin-ajax.php", // this is the object instantiated in wp_localize_script function
					type: 'POST',
					data: {
						action: 'print_voucher', // this is the function in your functions.php that will be triggered
						order: elm.getAttribute('order'),

					},
					success: function(data) {
						//Do something with the result from server

						console.log(data);
						window.location = data;
						//location.reload();


					}
				});


			}



			function rejectorder(elm) {


				message = jQuery('#reject_reason' + elm.getAttribute('order')).val()

				jQuery.ajax({
					url: "/wp-admin/admin-ajax.php", // this is the object instantiated in wp_localize_script function
					type: 'POST',
					data: {
						action: 'reject', // this is the function in your functions.php that will be triggered
						order: elm.getAttribute('order'),
						message: message,

					},
					success: function(data) {
						//Do something with the result from server
						console.log(data);
						//location.reload();


					}
				});


			}
		</script>
		<?php $cron = new get_data_local();
					$cron->get_shippings();
					//var_dump($cron->get_complete());
		?>

		<div class="wrap">
			<h2>ShopFlix Orders</h2>
			<p></p>
			<div class="modals"></div>
			<div class="row">
				<div class="col-md-12">
					<table class="table">
						<thead>
							<tr>
								<th scope="col">#</th>
								<th scope="col">OrderID</th>
								<th scope="col">Shipping Id</th>
								<th scope="col">First Name</th>
								<th scope="col">Last Name</th>
								<th scope="col">Status</th>
								<th scope="col">Track Number</th>
								<th scope="col">Track Number</th>
								<th scope="col">Voucher</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>

			</div>


			<p></p>
			<?php settings_errors(); ?>

			<div class="row">
				<div class="col-md-6">

				</div>

			</div>

		</div>
<?php
				}

				public function marketplaceapishoppings_page_init()
				{
				}
			}


			if (is_admin())
				$marketplaceapishippings = new Shopflix_shiiping();
?>