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
    <title>Read Tag - NodeMCU ESP8266 Project</title>

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
        .container-section { max-width: 700px; margin-top: 40px; }
        table th, table td { vertical-align: middle; }
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
        <a href="registration.php"><i class="bi bi-person-plus-fill"></i> Registration</a>
        <a href="read tag.php" class="active"><i class="bi bi-upc-scan"></i> Read Tag</a>
        <a href="logout.php" class="logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>

    <!-- Overlay -->
    <div id="overlay" onclick="toggleSidebar()"></div>

    <!-- Read Tag Section -->
    <div id="content" class="container container-section">
        <div class="card p-4 shadow">
            <h3 class="text-center mb-4" id="blink">Please Scan Tag to Display ID or User Data</h3>
            <p id="getUID" hidden></p>

            <div id="show_user_data" class="table-responsive">
                <table class="table table-striped table-bordered text-center">
                    <thead>
                        <tr><th colspan="3">User Data</th></tr>
                    </thead>
                    <tbody>
                        <tr><td><strong>ID</strong></td><td>:</td><td>--------</td></tr>
                        <tr><td><strong>Name</strong></td><td>:</td><td>--------</td></tr>
                        <tr><td><strong>Gender</strong></td><td>:</td><td>--------</td></tr>
                        <tr><td><strong>Email</strong></td><td>:</td><td>--------</td></tr>
                        <tr><td><strong>Mobile</strong></td><td>:</td><td>--------</td></tr>
                    </tbody>
                </table>
            </div>
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

    <!-- Script to Fetch User Data -->
    <script>
        var myVar = setInterval(myTimer, 1000);
        var myVar1 = setInterval(myTimer1, 1000);
        var oldID = "";
        clearInterval(myVar1);

        function myTimer() {
            var getID = document.getElementById("getUID").innerHTML;
            oldID = getID;
            if(getID != "") {
                myVar1 = setInterval(myTimer1, 500);
                showUser(getID);
                clearInterval(myVar);
            }
        }

        function myTimer1() {
            var getID = document.getElementById("getUID").innerHTML;
            if(oldID != getID) {
                myVar = setInterval(myTimer, 500);
                clearInterval(myVar1);
            }
        }

        function showUser(str) {
            if (str == "") {
                document.getElementById("show_user_data").innerHTML = "";
                return;
            } else {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("show_user_data").innerHTML = this.responseText;
                    }
                };
                xmlhttp.open("GET","read tag user data.php?id="+str,true);
                xmlhttp.send();
            }
        }

        var blink = document.getElementById('blink');
        setInterval(function() {
            blink.style.opacity = (blink.style.opacity == 0 ? 1 : 0);
        }, 750);
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
