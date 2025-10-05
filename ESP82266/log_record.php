<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require 'database.php';

// connect
$pdo = Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// clear logs if requested
if (isset($_POST['clear_logs'])) {
    $pdo->exec("DELETE FROM log_records");
    $pdo->exec("ALTER TABLE log_records AUTO_INCREMENT = 1"); // reset ID counter
    header("Location: log_record.php"); // refresh page after delete
    exit();
}

// fetch logs
$sql = "SELECT * FROM log_records ORDER BY scan_date DESC";
$q = $pdo->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log Records - NodeMCU ESP8266 Project</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

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
        <a href="log_record.php" class="active"><i class="bi bi-clipboard-data"></i> Log Record</a>
        <a href="user data.php"><i class="bi bi-people-fill"></i> User Data</a>
        <a href="registration.php"><i class="bi bi-person-plus-fill"></i> Registration</a>
        <a href="read tag.php"><i class="bi bi-upc-scan"></i> Read Tag</a>
        <a href="logout.php" class="logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>

    <!-- Overlay -->
    <div id="overlay" onclick="toggleSidebar()"></div>

    <!-- Log Records Section -->
    <div id="content" class="container table-container text-center mt-4">
        <h2 class="mb-3">Log Records 📋</h2>
        <p class="lead">Scan history of all registered RFID tags</p>

        <!-- Export + Clear Buttons -->
        <div class="mb-3 text-end">
            <a href="export_log.php" class="btn btn-success">Export to CSV</a>
            <form method="post" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete all log records?');">
                <button type="submit" name="clear_logs" class="btn btn-danger">Clear All Logs</button>
            </form>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Card ID</th>
                                <th>Name</th>
                                <th>Date</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $q->fetch(PDO::FETCH_ASSOC)): 
                                $date = date("Y-m-d", strtotime($row['scan_date']));
                                $time = date("H:i:s", strtotime($row['scan_date']));
                            ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['card_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo $date; ?></td>
                                    <td><?php echo $time; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
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
                // Close
                sidebar.style.left = "-250px";
                overlay.style.width = "0";
                menuIcon.innerHTML = '<i class="bi bi-list"></i>';
            } else {
                // Open
                sidebar.style.left = "0";
                overlay.style.width = "100%";
                menuIcon.innerHTML = '<i class="bi bi-x-lg"></i>';
            }
        }
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php Database::disconnect(); ?>
