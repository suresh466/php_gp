<?php

class Shoes
{
    private $db;

    function __construct($db)
    {
        $this->db = $db;
    }

    function add_shoe($shoe_name, $shoe_price, $shoe_size, $shoe_color, $shoe_brand, $category_id, $picture)
    {
        $shoe_name_clean = $this->db->prepare_string($shoe_name);
        $shoe_price_clean = $this->db->prepare_string($shoe_price);
        $shoe_size_clean = $this->db->prepare_string($shoe_size);
        $shoe_color_clean = $this->db->prepare_string($shoe_color);
        $shoe_brand_clean = $this->db->prepare_string($shoe_brand);
        $category_id_clean = $this->db->prepare_string($category_id);
        $picture_clean = $this->db->prepare_string($picture);

        $query = "INSERT INTO shoes(shoe_name, shoe_price, shoe_size, shoe_color, shoe_brand, category_id, picture) VALUES (?,?,?,?,?,?,?)";

        $stmt = mysqli_prepare($this->db->get_dbc(), $query);

        mysqli_stmt_bind_param(
            $stmt,
            'sssssss',
            $shoe_name_clean,
            $shoe_price_clean,
            $shoe_size_clean,
            $shoe_color_clean,
            $shoe_brand_clean,
            $category_id_clean,
            $picture_clean,
        );

        $result = mysqli_stmt_execute($stmt);

        return $result;
    }

    function get_shoes($category = null)
    {
        if ($category) {
            $category_clean = $this->db->prepare_string($category);
            $query = "SELECT * FROM shoes WHERE category_id = ?";
            $stmt = mysqli_prepare($this->db->get_dbc(), $query);
            mysqli_stmt_bind_param($stmt, 's', $category_clean);
        } else {
            $query = "SELECT * FROM shoes";
            $stmt = mysqli_prepare($this->db->get_dbc(), $query);
        }
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return $result;
    }
    function get_shoe_by_id($shoe_id)
    {
        $shoe_id_clean = $this->db->prepare_string($shoe_id);
        $query = "SELECT * FROM shoes WHERE shoe_id = ?";
        $stmt = mysqli_prepare($this->db->get_dbc(), $query);
        mysqli_stmt_bind_param($stmt, 's', $shoe_id_clean);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return $result;
    }

    function update_shoe_by_id($shoe_id, $shoe_name, $shoe_price, $shoe_size, $shoe_color, $shoe_brand, $category_id, $picture)
    {

        $shoe_id_clean = $this->db->prepare_string($shoe_id);
        $shoe_name_clean = $this->db->prepare_string($shoe_name);
        $shoe_price_clean = $this->db->prepare_string($shoe_price);
        $shoe_size_clean = $this->db->prepare_string($shoe_size);
        $shoe_color_clean = $this->db->prepare_string($shoe_color);
        $shoe_brand_clean = $this->db->prepare_string($shoe_brand);
        $category_id_clean = $this->db->prepare_string($category_id);
        $picture_clean = $this->db->prepare_string($picture);

        $query = "UPDATE shoes SET shoe_name = ?, shoe_price = ?, shoe_size = ?, shoe_color = ?, shoe_brand = ?, category_id = ?, picture = ? WHERE  shoe_id = ?;";

        $stmt = mysqli_prepare($this->db->get_dbc(), $query);

        mysqli_stmt_bind_param(
            $stmt,
            'ssssssss',
            $shoe_name_clean,
            $shoe_price_clean,
            $shoe_size_clean,
            $shoe_color_clean,
            $shoe_brand_clean,
            $category_id_clean,
            $picture_clean,
            $shoe_id_clean
        );

        $result = mysqli_stmt_execute($stmt);

        return $result;
    }

    function delete_shoe_by_id($shoe_id)
    {
        $shoe_id_clean = $this->db->prepare_string($shoe_id);
        $query = "DELETE FROM shoes WHERE shoe_id = ?";
        $stmt = mysqli_prepare($this->db->get_dbc(), $query);
        mysqli_stmt_bind_param($stmt, 's', $shoe_id_clean);
        $result = mysqli_stmt_execute($stmt);
        return $result;
    }
}