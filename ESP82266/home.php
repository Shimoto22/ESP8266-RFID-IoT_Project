<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<?php
$Write="<?php $" . "UIDresult=''; " . "echo $" . "UIDresult;" . " ?>";
file_put_contents('UIDContainer.php',$Write);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home - NodeMCU ESP8266 Project</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body { font-family: Arial, sans-serif; }
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
        #content { transition: margin-left 0.3s; padding: 20px; }
        .menu-btn { font-size: 28px; cursor: pointer; color: white; }
        .navbar { z-index: 1000; }
        .card { border-radius: 15px; }
        .info-card { transition: transform 0.2s; }
        .info-card:hover { transform: translateY(-5px); }
        .card h5 { font-weight: bold; font-size: 1.2rem; }
        .card p { font-size: 0.95rem; }
        footer small { font-size: 0.9rem; color: #6c757d; }
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
        <a href="read tag.php"><i class="bi bi-upc-scan"></i> Read Tag</a>
        <a href="logout.php" class="logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>

    <!-- Overlay -->
    <div id="overlay" onclick="toggleSidebar()"></div>

    <!-- Page Content -->
    <div id="content" class="container text-center welcome">
        <h2 class="mb-3">Welcome, <?php echo $_SESSION['username']; ?> 👋</h2>
        <img src="iot.png" alt="IoT Project" class="img-fluid mt-3" style="max-width:70%;">

        <!-- Dashboard Info Section -->
        <div class="container mt-5">
            <div class="row g-4">
                <!-- Log Records -->
                <div class="col-md-3">
                    <div class="card shadow text-center info-card p-3">
                        <i class="bi bi-clipboard-data display-4 text-primary"></i>
                        <h5 class="mt-3">Log Records</h5>
                        <p class="text-muted">View all RFID scan history.</p>
                        <a href="log_record.php" class="btn btn-primary btn-sm">View Logs</a>
                        <hr>
                        <small class="text-secondary">Check daily, weekly, or monthly scan activities of all users.</small>
                    </div>
                </div>

                <!-- User Data -->
                <div class="col-md-3">
                    <div class="card shadow text-center info-card p-3">
                        <i class="bi bi-people-fill display-4 text-success"></i>
                        <h5 class="mt-3">User Data</h5>
                        <p class="text-muted">Manage registered users.</p>
                        <a href="user data.php" class="btn btn-success btn-sm">Manage Users</a>
                        <hr>
                        <small class="text-secondary">Add, edit, or remove RFID users and update their personal information.</small>
                    </div>
                </div>

                <!-- Registration -->
                <div class="col-md-3">
                    <div class="card shadow text-center info-card p-3">
                        <i class="bi bi-person-plus-fill display-4 text-warning"></i>
                        <h5 class="mt-3">Registration</h5>
                        <p class="text-muted">Add new RFID tags and users.</p>
                        <a href="registration.php" class="btn btn-warning btn-sm text-white">Register User</a>
                        <hr>
                        <small class="text-secondary">Register new RFID cards, assign them to users, and store details in the database.</small>
                    </div>
                </div>

                <!-- Read Tag -->
                <div class="col-md-3">
                    <div class="card shadow text-center info-card p-3">
                        <i class="bi bi-upc-scan display-4 text-danger"></i>
                        <h5 class="mt-3">Read Tag</h5>
                        <p class="text-muted">Scan an RFID tag to view details.</p>
                        <a href="read tag.php" class="btn btn-danger btn-sm">Read Now</a>
                        <hr>
                        <small class="text-secondary">Use this tool to quickly test or verify RFID tags with the system.</small>
                    </div>
                </div>
            </div>

            <!-- System Overview -->
            <div class="row mt-5">
                <div class="col-md-12">
                    <div class="card shadow p-4 text-start">
                        <h4 class="text-primary"><i class="bi bi-info-circle-fill"></i> System Overview</h4>
                        <p>This portal is designed to manage RFID-based access control using NodeMCU ESP8266. It integrates real-time tag scanning, user management, and data logging into a MySQL database.</p>
                        <ul>
                            <li><strong>Hardware:</strong> NodeMCU V3 ESP8266 / ESP12E</li>
                            <li><strong>Database:</strong> MySQL (PHPMyAdmin)</li>
                            <li><strong>Features:</strong> RFID registration, scan logging, user data management</li>
                            <li><strong>Security:</strong> Session-based access control</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-light text-center mt-5 p-3 shadow-sm">
        <small>&copy; <?php echo date("Y"); ?> NodeMCU RFID IoT Project. All Rights Reserved.</small>
    </footer>

    <!-- Scripts -->
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
</body>
</html>
