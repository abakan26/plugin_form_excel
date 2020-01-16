<?php


class WPShippingCustom
{
    public function __construct()
    {

    }

    public function shipping()
    {

    }
    public function get_orders($wpdb)
    {
        $orders = $wpdb->get_results(
            "SELECT /*order_items.order_id,*/ order_items.order_item_name as product_name,
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
        return $orders;
    }
}