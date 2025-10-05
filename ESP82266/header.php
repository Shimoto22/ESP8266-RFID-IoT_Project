<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $pageTitle ?? "IoT Dashboard"; ?></title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #4bc5ee, #1a73e8);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card {
            width: 1000px;
            border-radius: 20px;
            box-shadow: 0px 8px 20px rgba(0,0,0,0.2);
            padding: 30px;
            background: #fff;
        }
        .navbar-custom {
            background: #1a73e8;
            border-radius: 15px;
        }
        .navbar-custom .nav-link {
            color: white !important;
            font-weight: 500;
            padding: 10px 20px;
        }
        .navbar-custom .nav-link.active {
            background: #0c47a1;
            border-radius: 10px;
        }
        h2, h3 {
            color: #333;
        }
    </style>
</head>
<body>
<div class="card">
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-custom mb-4">
        <div class="container-fluid">
            <a class="navbar-brand text-white fw-bold">IoT Dashboard</a>
            <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link <?php if($pageTitle=='Home') echo 'active'; ?>" href="home.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link <?php if($pageTitle=='User Data') echo 'active'; ?>" href="user data.php">User Data</a></li>
                    <li class="nav-item"><a class="nav-link <?php if($pageTitle=='Registration') echo 'active'; ?>" href="registration.php">Registration</a></li>
                    <li class="nav-item"><a class="nav-link <?php if($pageTitle=='Read RFID Tag') echo 'active'; ?>" href="read tag.php">Read RFID Tag</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
