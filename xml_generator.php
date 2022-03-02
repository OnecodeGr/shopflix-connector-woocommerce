<?php
require_once('simplexml.php');
class XML_generator
{



    public function generate_xml()
    {

        if (!defined('ABSPATH'))
            exit; // Exit if accessed directly

        global $wpdb;

        $marketplaceapisettings_options = get_option('marketplaceapisettings_option_name');

        if (!isset($marketplaceapisettings_options['hidden_fordel_8']) || trim($marketplaceapisettings_options['hidden_fordel_8']) === '') {

            $folder = $this->getRandomString();
            $marketplaceapisettings_options['hidden_fordel_8'] =  $folder;
            update_option('marketplaceapisettings_option_name', $marketplaceapisettings_options);
        } else {

            $folder = $marketplaceapisettings_options['hidden_fordel_8']; // MPN

        }




        if (!file_exists(wp_upload_dir()['basedir'] . '/' . $folder)) {
            wp_mkdir_p(wp_upload_dir()['basedir'] . '/' . $folder);
        }

        if (!file_exists(wp_upload_dir()['basedir'] . '/' . $folder . '/feed.xml')) {
            touch(wp_upload_dir()['basedir'] . '/' . $folder . '/feed.xml');
        }

        if (file_exists(wp_upload_dir()['basedir'] . '/' . $folder . '/feed.xml')) {
            $xmlFile = wp_upload_dir()['basedir'] . '/' . $folder . '/feed.xml';
        } else {
            echo "Could not create file.";
        }

        $xml_core = new feed_shopflixXMLExtended('<?xml version="1.0" encoding="utf-8"?><webstore/>');
        $xml = $xml_core->addChild('store');
        $xml->addAttribute('name', get_bloginfo('name'));
        $xml->addAttribute('url', get_site_url());
        $xml->addAttribute('encoding', 'utf-8');
        $now = date('Y-n-j G:i');
        $meta = $xml->addChild('meta');
        $meta->last_updated_at = time();
        $meta->store_code = 'el';
        $meta->store_name = get_bloginfo('name');
        $meta->locale = 'el_GR';
        $meta->count = 'count';

        $xml->addChild('created_at', "$now");
        $products = $xml->addChild('products');

        $args = array(
            'post_type' => 'product', 'posts_per_page'   => -1, 'orderby' => 'rand',  'post_status' => array('publish'),  'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => '_stock_status',
                    'value' =>  array('instock')
                )
            )
        );

        $loop = new WP_Query($args);
        while ($loop->have_posts()) : $loop->the_post();
            global $product, $post;
            $protitle = $product->get_title();
            $product_full_description =  $product->get_description();
            $product_id = $product->get_id();
            $marketplaceapisettings_options = get_option('marketplaceapisettings_option_name');
            if (isset($marketplaceapisettings_options['manufacturer_10']) && $marketplaceapisettings_options['manufacturer_10'] != '0') {

                $manufacturer_va = get_post_meta($product_id, $marketplaceapisettings_options['manufacturer_10'], true);
            } else {
                $manufacturer_va =  $product->get_attribute('pa_manufacturer');
            }

            if ($product->is_type('variable')) {


                $variations = $product->get_available_variations();
                foreach ($variations as $variation) {


                    $varid = $variation['variation_id'];

                    if (get_post_meta($varid, '_enable_in_shopflix', true) === 'no') {
                    } else {
                        if (isset($marketplaceapisettings_options['barcode_9']) && $marketplaceapisettings_options['barcode_9'] != '0') {

                            $ean = get_post_meta($varid, $marketplaceapisettings_options['barcode_9'], true);
                        } else {
                            //$ean =  get_post_meta($varid, 'ean_shopflix', true);
                            $ean = $_SERVER['SERVER_NAME'] . "-" . get_post_meta($variation['variation_id'], '_sku', true);
                        }

                        if (isset($marketplaceapisettings_options['mpn_7']) && $marketplaceapisettings_options['mpn_7'] != '0') {

                            $mpn = get_post_meta($varid, $marketplaceapisettings_options['mpn_7'], true);
                        } else {
                            $mpn =  get_post_meta($varid, 'mpn_shopflix', true);
                        }


                        $variation_o = new WC_Product_Variation($variation['variation_id']);
                        $var_sku = get_post_meta($variation['variation_id'], '_sku', true);
                        $product_ar = $products->addChild('product');
                        $product_ar->product_id =  $varid;
                        $product_ar->sku =  $var_sku;
                        $product_ar->mpn = $mpn;
                        $product_ar->ean = $ean;
                        $product_ar->name = NULL;
                        $product_ar->name->addCData($protitle);
                        $price = $variation['display_price'];
                        $product_ar->addChild('price', $price);
                        $product_ar->list_price = $variation_o->get_regular_price();
                        $url = get_permalink($variation['variation_id']);
                        $product_ar->link = NULL;
                        $product_ar->link->addCData($url);
                        if (empty(get_post_meta($varid, 'shipping_lead_time_shopflix', true))) {

                            $product_ar->shipping_lead_time = 0;
                        } else {

                            $product_ar->shipping_lead_time = get_post_meta($varid, 'shipping_lead_time_shopflix', true);
                        }
                        $product_ar->offer_from = get_post_meta($varid, 'offer_from_shopflix', true);
                        $product_ar->offer_to = get_post_meta($varid, 'offer_to_shopflix', true);
                        $product_ar->offer_price = get_post_meta($varid, 'offer_price_shopflix', true);
                        $product_ar->offer_quantity = get_post_meta($varid, 'offer_quantity_shopflix', true);
                        $variation_o->get_stock_quantity();
                        $product_ar->quantity = $variation_o->get_stock_quantity();
                        $attachment_url = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full', false)[0];
                        $product_ar->image = NULL;
                        $product_ar->image->addCData($attachment_url);
                        $product_ar->description = NULL;
                        $product_ar->description->addCData($product_full_description);
                        $product_ar->weight = $variation['weight_html'];
                        $product_ar->manufacturer = $manufacturer_va;
                        $product_ar->category  = NULL;
                        $product_ar->category->addCData($this->list_product_categories($product_id));
                    }
                }
            } else {


                if ($product->get_regular_price() == "") {
                    $cost = $product->get_price();
                } else {
                    $cost = $product->get_price();
                }

                if (get_post_meta($product_id, '_enable_in_shopflix', true) === 'no') {
                } else {


                    if (isset($marketplaceapisettings_options['barcode_9']) && $marketplaceapisettings_options['barcode_9'] != '0') {

                        $ean = get_post_meta($product_id, $marketplaceapisettings_options['barcode_9'], true);
                    } else {
                        //$ean =  get_post_meta($product_id, 'ean_shopflix', true);
                        $ean = $_SERVER['SERVER_NAME'] . "-" . $product->get_sku();
                    }
                    if (isset($marketplaceapisettings_options['mpn_7'])  && $marketplaceapisettings_options['mpn_7'] != '0') {

                        $mpn = get_post_meta($product_id, $marketplaceapisettings_options['mpn_7'], true);
                    } else {
                        $mpn =  get_post_meta($product_id, 'mpn_shopflix', true);
                    }


                    $product_ar = $products->addChild('product');
                    $product_ar->product_id =  $product_id;
                    $product_ar->sku = $product->get_sku();
                    $product_ar->mpn = $mpn;
                    $product_ar->ean = $ean;
                    $product_ar->name->$protitle;
                    $product_ar->addChild('price', $cost);
                    $product_ar->list_price =  $product->get_regular_price();
                    $url = get_permalink($product_id);
                    $product_ar->link = NULL;
                    $product_ar->link->addCData($url);
                    if (empty(get_post_meta($product_id, 'shipping_lead_time_shopflix', true))) {
                        $product_ar->shipping_lead_time = 0;
                    } else {

                        $product_ar->shipping_lead_time = get_post_meta($product_id, 'shipping_lead_time_shopflix', true);
                    }

                    $product_ar->offer_from = get_post_meta($product_id, 'offer_from_shopflix', true);
                    $product_ar->offer_to = get_post_meta($product_id, 'offer_to_shopflix', true);
                    $product_ar->offer_price = get_post_meta($product_id, 'offer_price_shopflix', true);
                    $product_ar->offer_quantity = get_post_meta($product_id, 'offer_quantity_shopflix', true);
                    $product_ar->quantity = $product->get_stock_quantity();
                    $attachment_url = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full', false)[0];
                    $product_ar->image = NULL;
                    $product_ar->image->addCData($attachment_url);
                    $product_ar->description = NULL;
                    $product_ar->description->addCData($product_full_description);
                    $product_ar->weight = $product->get_weight();
                    $product_ar->manufacturer =  $manufacturer_va;
                    $product_ar->category = NULL;
                    $product_ar->category->addCData($this->list_product_categories($product_id));
                }
            }

        endwhile;

        $xml->saveXML($xmlFile);
    }







    public function getRandomString($length = 12)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $string = '';

        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, strlen($characters) - 1)];
        }

        return $string;
    }

    public function list_product_categories($atts)
    {


        $output    = []; // Initialising
        $taxonomy  = 'product_cat'; // Taxonomy for product category

        // Get the product categories terms ids in the product:
        $terms_ids = wp_get_post_terms($atts, $taxonomy, array('fields' => 'ids'));

        $i = 0;

        // Loop though terms ids (product categories)
        foreach ($terms_ids as $term_id) {
            $term_names = []; // Initialising category array

            // Loop through product category ancestors
            foreach (get_ancestors($term_id, $taxonomy) as $ancestor_id) {
                // Add the ancestors term names to the category array
                $term_names[] = get_term($ancestor_id, $taxonomy)->name;
            }
            // Add the product category term name to the category array
            $term_names[] = get_term($term_id, $taxonomy)->name;

            // Add the formatted ancestors with the product category to main array
            $i++;
        }

        if ($i == 0) {

            $term_names = []; // Initialising category array
            $term_names[] = 'uncategories';
        }
        $output = implode(', ', $term_names);
        //echo $output;


        // Output the formatted product categories with their ancestors
        return $output;
    }




    public function attribute_slug_to_title($attribute, $slug)
    {
        global $woocommerce;
        if (taxonomy_exists(esc_attr(str_replace('attribute_', '', $attribute)))) {
            $term = get_term_by('slug', $slug, esc_attr(str_replace('attribute_', '', $attribute)));
            if (!is_wp_error($term) && $term->name)
                $value = $term->name;
        } else {
            $value = apply_filters('woocommerce_variation_option_name', $value);
        }
        return $value;
    }
}
