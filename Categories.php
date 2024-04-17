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
}