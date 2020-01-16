<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://plugin.com/
 * @since      1.0.0
 *
 * @package    Vnshipping
 * @subpackage Vnshipping/admin/partials
 */
?>

    <!-- This file should primarily consist of HTML with a little bit of PHP. -->

    <div>
        <form action="" method="post">
            <div>
                <input type="submit" name="submit">
                <input type="hidden" name="action" value="shipping">
            </div>
        </form>
    </div>
<?php
//$a = wp_query("SELECT * FROM 'wp_woocommerce_order_items'");
//var_dump($a);
global $wpdb;

function vardump($var)
{
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
}

$a = new WPShippingCustom();

vardump($a ->get_orders($wpdb));

?>