
<?php
$Write="<?php $" . "UIDresult=''; " . "echo $" . "UIDresult;" . " ?>";
file_put_contents('UIDContainer.php',$Write);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Data - NodeMCU ESP8266</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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

        /* Table improvements */
        .table th, .table td {
            border: 1px solid #dee2e6;
            padding: 10px;
            vertical-align: middle;
        }
        .table th { background-color: #f8f9fa; text-align: center; }
        .table td { text-align: center; }
        .gender-text { font-weight: bold; color: black; }
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
        <a href="user data.php" class="active"><i class="bi bi-people-fill"></i> User Data</a>
        <a href="registration.php"><i class="bi bi-person-plus-fill"></i> Registration</a>
        <a href="read tag.php"><i class="bi bi-upc-scan"></i> Read Tag</a>
        <a href="logout.php" class="logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>

    <!-- Overlay -->
    <div id="overlay" onclick="toggleSidebar()"></div>

    <!-- User Data Section -->
    <div id="content" class="container mt-4">

        <?php if (isset($_GET['unlocked']) && $_GET['unlocked'] == 1): ?>
            <div class="alert alert-success text-center fw-bold shadow" role="alert">
                ✅ Card successfully unlocked!
            </div>
        <?php endif; ?>

        <h2 class="text-center">User Data Table</h2>
        <div class="table-container mt-4">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>ID</th>
                            <th>Gender</th>
                            <th>Email</th>
                            <th>Mobile Number</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    include 'database.php';
                    $pdo = Database::connect();
                    $sql = 'SELECT * FROM table_the_iot_projects ORDER BY name ASC';
                    foreach ($pdo->query($sql) as $row) {
                        echo '<tr>';
                        echo '<td><i class="bi bi-person-circle me-1 text-primary"></i>'. htmlspecialchars($row['name']) . '</td>';
                        echo '<td><span class="badge bg-secondary">'. htmlspecialchars($row['id']) .'</span></td>';
                        echo '<td class="gender-text">'. strtoupper(htmlspecialchars($row['gender'])) .'</td>';
                        echo '<td><a href="mailto:'. htmlspecialchars($row['email']) .'" class="text-decoration-none"><i class="bi bi-envelope-fill me-1 text-danger"></i>'. htmlspecialchars($row['email']) .'</a></td>';
                        echo '<td><a href="tel:'. htmlspecialchars($row['mobile']) .'" class="text-decoration-none"><i class="bi bi-telephone-fill me-1 text-success"></i>'. htmlspecialchars($row['mobile']) .'</a></td>';
                        echo '<td>
                                <a class="btn btn-sm btn-outline-success" href="user data edit page.php?id='.$row['id'].'"><i class="bi bi-pencil-square"></i> Edit</a>
                                <a class="btn btn-sm btn-outline-danger" href="user data delete page.php?id='.$row['id'].'"><i class="bi bi-trash"></i> Delete</a>';
                        if (isset($row['locked']) && $row['locked'] == 1) {
                            echo ' <a class="btn btn-sm btn-warning" href="unlock.php?id='.$row['id'].'"><i class="bi bi-unlock"></i> Unlock</a>';
                        }
                        echo '</td>';
                        echo '</tr>';
                    }
                    Database::disconnect();
                    ?>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

