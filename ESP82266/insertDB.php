<?php
require 'database.php';

if (!empty($_POST)) {
    // keep track post values
    $name = $_POST['name'];
    $id = $_POST['id'];  // this is actually the card_id
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    
    // insert into user data table
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO table_the_iot_projects (name,id,gender,email,mobile) VALUES (?, ?, ?, ?, ?)";
    $q = $pdo->prepare($sql);
    $q->execute(array($name, $id, $gender, $email, $mobile));

    // also insert into log_records
    $sql2 = "INSERT INTO log_records (card_id, name, scan_date) VALUES (?, ?, NOW())";
    $q2 = $pdo->prepare($sql2);
    $q2->execute(array($id, $name));

    Database::disconnect();
    header("Location: user data.php");
}
?>
