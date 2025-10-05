<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$Write="<?php $" . "UIDresult=''; " . "echo $" . "UIDresult;" . " ?>";
file_put_contents('UIDContainer.php',$Write);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registration - NodeMCU ESP8266 Project</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#getUID").load("UIDContainer.php");
            setInterval(function() {
                $("#getUID").load("UIDContainer.php");
            }, 500);
        });
    </script>

    <style>
        body { font-family: Arial, sans-serif; }
        /* Sidebar */
        #sidebar {
            position: fixed;
            top: 0;
            left: -250px;
            height: 100%;
            width: 250px;
            background: #0d6efd;
            padding-top: 60px;
            transition: 0.3s;
            z-index: 999;
        }
        #sidebar a {
            padding: 15px 20px;
            display: block;
            color: white;
            text-decoration: none;
            transition: 0.2s;
        }
        #sidebar a:hover { background: #0b5ed7; }
        #sidebar .logout { color: #ffc107; }
        #sidebar a.active { background: #0b5ed7; font-weight: bold; }
        /* Overlay */
        #overlay {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 0;
            background: rgba(0, 0, 0, 0.5);
            transition: 0.3s;
            z-index: 998;
        }
        /* Content */
        #content { transition: margin-left 0.3s; padding: 20px; }
        .menu-btn { font-size: 28px; cursor: pointer; color: white; }
        .navbar { z-index: 1000; }

        /* Card adjustments */
        .container-section { max-width: 600px; margin-top: 40px; }
        .btn-custom { background-color: #0d6efd; color: white; }
        .btn-custom:hover { background-color: #0b5ed7; color: white; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-primary shadow p-3">
        <span class="menu-btn" onclick="toggleSidebar()" id="menuIcon"><i class="bi bi-list"></i></span>
        <a class="navbar-brand ms-3" href="home.php">SUBDIVISION PORTAL</a>
    </nav>

    <!-- Sidebar -->
    <div id="sidebar">
        <a href="home.php"><i class="bi bi-house-door"></i> Home</a>
        <a href="log_record.php"><i class="bi bi-clipboard-data"></i> Log Record</a>
        <a href="user data.php"><i class="bi bi-people-fill"></i> User Data</a>
        <a href="registration.php" class="active"><i class="bi bi-person-plus-fill"></i> Registration</a>
        <a href="read tag.php"><i class="bi bi-upc-scan"></i> Read Tag</a>
        <a href="logout.php" class="logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>

    <!-- Overlay -->
    <div id="overlay" onclick="toggleSidebar()"></div>

    <!-- Registration Form -->
    <div id="content" class="container container-section">
        <div class="card p-4 shadow">
            <h3 class="text-center mb-4">User Registration</h3>
            <form action="insertDB.php" method="post">
                <div class="mb-3">
                    <label for="getUID">Card ID</label>
                    <textarea class="form-control" name="id" id="getUID" rows="1" readonly
                        placeholder="Please scan your card/keychain to display ID"></textarea>
                </div>
                <div class="mb-3">
                    <label for="name">Full Name</label>
                    <input type="text" class="form-control" name="name" id="name" required>
                </div>
                <div class="mb-3">
                    <label for="gender">Gender</label>
                    <select class="form-control" name="gender" id="gender" required>
                        <option value="">-- Select Gender --</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="email">Email Address</label>
                    <input type="email" class="form-control" name="email" id="email" required>
                </div>
                <div class="mb-3">
                    <label for="mobile">Mobile Number</label>
                    <input type="text" class="form-control" name="mobile" id="mobile" required>
                </div>
                <button type="submit" class="btn btn-custom w-100">Save</button>
            </form>
        </div>
    </div>

    <!-- Sidebar Toggle Script -->
    <script>
        function toggleSidebar() {
            let sidebar = document.getElementById("sidebar");
            let overlay = document.getElementById("overlay");
            let menuIcon = document.getElementById("menuIcon");

            if (sidebar.style.left === "0px") {
                sidebar.style.left = "-250px";
                overlay.style.width = "0";
                menuIcon.innerHTML = '<i class="bi bi-list"></i>';
            } else {
                sidebar.style.left = "0";
                overlay.style.width = "100%";
                menuIcon.innerHTML = '<i class="bi bi-x-lg"></i>';
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
