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
$db = $wpdb->get_results("
SELECT *
FROM {$wpdb->prefix}woocommerce_order_items 

");
//WHERE `date_created`
//BETWEEN "2017-01-01 09:00:00" AND "2017-01-01 21:00:00"
//var_dump($db);
function vardump($var)
{
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
}


//$order = wc_get_order(503);
//$data =$order->get_data();
//$order_items = $order->get_items();
//echo $data['date_created']->date('Y-m-d H:i:s');
//echo "<br>";
//echo $data['total'];
//echo "<br>";
//foreach ($order_items as $order_item){
//    echo $order_item->get_name();
//}
$wpdb_order = $wpdb->get_results(
    "SELECT *
                FROM {$wpdb->prefix}woocommerce_order_items as order_items
                INNER JOIN $wpdb->posts as posts
                ON order_items.order_id = posts.ID
                WHERE post_type = 'shop_order'
                AND post_date BETWEEN '2020-01-16 00:00:00' AND '2020-01-16 23:59:59'
");
//echo "<hr>";
//vardump($wpdb_order);
//echo "<hr>";
$wpdb_orders = $wpdb->get_results(
    "SELECT *
                FROM $wpdb->posts
                WHERE post_type = 'shop_order'
                AND post_date BETWEEN '2020-01-16 00:00:00' AND '2020-01-16 23:59:59'
                
");
//echo "<hr>";
//vardump($wpdb_orders);
//echo "<hr>";
//foreach ($results as $order_id){
//    echo "<br>";
//    $id = $order_id->ID;
//    echo "Заказ " .  (string) $id;
//    foreach (wc_get_order($id)->get_items() as $order){
//        vardump($order->get_quantity());
//        vardump($order->get_name());
//    }
//    echo "<hr>";
//}

$wpdb_orders_1 = $wpdb->get_results(
    "SELECT order_items.order_id, order_items.order_item_name as product_name,
                product.ID as product_id, product_meta.meta_value as price,
                product_meta_wt.meta_value as productunit,
               order_items_meta2.meta_value as quantity

        FROM {$wpdb->prefix}woocommerce_order_items as order_items
        
        INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_items_meta1
        ON order_items.order_item_id = order_items_meta1.order_item_id
        AND order_items_meta1.meta_key = '_product_id'
        
        INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_items_meta2
        ON order_items.order_item_id = order_items_meta2.order_item_id
        AND order_items_meta2.meta_key = '_qty'
        
        INNER JOIN $wpdb->posts as product
        ON product.ID = order_items_meta1.meta_value
        AND product.post_type = 'product'
        
        INNER JOIN $wpdb->postmeta as product_meta
        ON product.ID = product_meta.post_id
        AND product_meta.meta_key = '_price'
        
        LEFT JOIN $wpdb->postmeta as product_meta_wt
        ON product_meta_wt.post_id = order_items_meta1.meta_value
        AND product_meta_wt.meta_key = '_productunit'
        
        
        INNER JOIN $wpdb->posts as posts
        ON order_items.order_id = posts.ID
        WHERE posts.post_type = 'shop_order'
        AND posts.post_date BETWEEN '2020-01-16 00:00:00' AND '2020-01-16 23:59:59'
");
vardump($wpdb_orders_1);

?>