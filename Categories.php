<?php

class Categories {
    private $db;

    function __construct($db)
    {
        $this->db = $db;
    }

    function get_categories()
    {
        $query = "SELECT * FROM categories";
        $stmt = mysqli_prepare($this->db->get_dbc(), $query);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return $result;
    }
    
    function get_category_by_id($category_id) {
        $query = "SELECT * FROM categories WHERE category_id = ?";
        $stmt = mysqli_prepare($this->db->get_dbc(), $query);
        mysqli_stmt_bind_param($stmt, 'i', $category_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return $result;
    }
}