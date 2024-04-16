<?php
class OrderItem {
    private $db;

    function __construct($db) {
        $this->db = $db;
    }
    
    function add_order_item($order_id, $shoe_id, $quantity)
    {
        $order_id_clean = $this->db->prepare_string($order_id);
        $product_id_clean = $this->db->prepare_string($shoe_id);
        $quantity_clean = $this->db->prepare_string($quantity);

        $query = "INSERT INTO order_items(order_id, shoe_id, quantity) VALUES (?,?,?)";

        $stmt = mysqli_prepare($this->db->get_dbc(), $query);

        mysqli_stmt_bind_param(
            $stmt,
            'sss',
            $order_id_clean,
            $product_id_clean,
            $quantity_clean,
        );

        $result = mysqli_stmt_execute($stmt);

        return $result;
    }
}