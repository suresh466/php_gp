<?php
class Order {
    private $db;

    function __construct($db)
    {
        $this->db = $db;
    }
    
    function add_order($user_id, $total_price)
    {
        $user_id_clean = $this->db->prepare_string($user_id);
        $total_price_clean = $this->db->prepare_string($total_price);

        $query = "INSERT INTO orders(user_id, total_price) VALUES (?,?)";

        $stmt = mysqli_prepare($this->db->get_dbc(), $query);

        mysqli_stmt_bind_param(
            $stmt,
            'ss',
            $user_id_clean,
            $total_price_clean,
        );

        $result = mysqli_stmt_execute($stmt);

        return $result;
    }
}