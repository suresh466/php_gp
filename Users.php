<?php

class Users {
    private $db;

    function __construct($db)
    {
        $this->db = $db;
    }
    
    function register_user($first_name, $last_name, $email, $phone, $province, $user_type='customer')
    {
        $first_name_clean = $this->db->prepare_string($first_name);
        $last_name_clean = $this->db->prepare_string($last_name);
        $email_clean = $this->db->prepare_string($email);
        $phone_clean = $this->db->prepare_string($phone);
        $province_clean = $this->db->prepare_string($province);
        $user_type_clean = $this->db->prepare_string($user_type);

        $query = "INSERT INTO users(first_name, last_name, email, phone, province, user_type) VALUES (?,?,?,?,?,?)";

        $stmt = mysqli_prepare($this->db->get_dbc(), $query);

    // check if user is logged in
        mysqli_stmt_bind_param(
            $stmt,
            'ssssss',
            $first_name_clean,
            $last_name_clean,
            $email_clean,
            $phone_clean,
            $province_clean,
            $user_type_clean,
        );

        $result = mysqli_stmt_execute($stmt);

        return $result;
    }
    
    function get_user_by_id($User_id) {
        $user_id_clean = $this->db->prepare_string($User_id);
        $query = "SELECT * FROM users WHERE user_id = ?";
        $stmt = mysqli_prepare($this->db->get_dbc(), $query);
        mysqli_stmt_bind_param($stmt, 's', $user_id_clean);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return $result;
    }
    
}