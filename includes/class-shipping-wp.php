<?php



class WPShippingCustom
{
    public function __construct()
    {

    }

    public function shipping()
    {

    }

    private function get_no_group_orders($wpdb, $start, $end)
    {
        $orders = $wpdb->prepare(
            "SELECT /*order_items.order_id,*/ order_items.order_item_name as product_name,
                product.ID as product_id, product_meta.meta_value as price,
                product_meta_wt.meta_value as productunit, postmeta_weight.meta_value as weight,
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
        
        LEFT JOIN {$wpdb->prefix}postmeta as postmeta_weight
        ON postmeta_weight.meta_key = '_weight'
        AND postmeta_weight.post_id = order_items_meta1.meta_value
        
        INNER JOIN $wpdb->posts as posts
        ON order_items.order_id = posts.ID
        WHERE posts.post_type = 'shop_order'
        AND posts.post_date BETWEEN %s AND %s
", $start, $end);

        return $wpdb->get_results($orders);
    }

    private function sum_order_item_quantity($order_item)
    {
        $quantity = array_reduce(
            $order_item,
            function ($carry, $item) {
                $carry += (int)$item->quantity;
                return $carry;
            }
        );
        return $quantity;
    }

    private function groupOrderByProductID($no_group_orders)
    {
        $groupedOrderByProductID = array();
        foreach ($no_group_orders as $row) {
            $groupedOrderByProductID[$row->product_id][] = $row;
        };
        return $groupedOrderByProductID;
    }

    private function mergeOrderByProductID($groupedOrderByProductID)
    {
        $mergedOrderByProductID = array();
        $mergedOrderByProductID = array_map(
            function ($order_item) {
                $quantity = $this->sum_order_item_quantity($order_item);
                $order_item[0]->quantity = $quantity;
                return $order_item[0];
            },
            $groupedOrderByProductID
        );

        return $mergedOrderByProductID;
    }

    public function get_orders($wpdb, $start, $end)
    {
        $no_group_orders = $this->get_no_group_orders($wpdb, $start, $end);
        $groupedOrderByProductID = $this->groupOrderByProductID($no_group_orders);
        return $this->mergeOrderByProductID($groupedOrderByProductID);
    }


}