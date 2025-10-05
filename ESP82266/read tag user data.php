<?php
require 'database.php';
$id = null;
if (!empty($_GET['id'])) {
    $id = $_REQUEST['id'];
}

$pdo = Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// check if user exists
$sql = "SELECT * FROM table_the_iot_projects WHERE id = ?";
$q = $pdo->prepare($sql);
$q->execute([$id]);
$data = $q->fetch(PDO::FETCH_ASSOC);

$msg = "";
$msg_type = "danger"; // default red

if ($data && $data['name'] != null) {
    $today = date("Y-m-d");

    // initialize default values if columns don't exist yet
    if (!isset($data['daily_count'])) $data['daily_count'] = 0;
    if (!isset($data['last_used'])) $data['last_used'] = $today;
    if (!isset($data['is_locked'])) $data['is_locked'] = 0;

    // reset daily_count if new day
    if ($data['last_used'] != $today) {
        $data['daily_count'] = 0;
        $sql_reset = "UPDATE table_the_iot_projects SET daily_count=0, last_used=? WHERE id=?";
        $q_reset = $pdo->prepare($sql_reset);
        $q_reset->execute([$today, $id]);
    }

    if ($data['is_locked'] == 1) {
        // already locked
        $msg = "🔒 Your ID is LOCKED - Please contact Admin to unlock.";
        $msg_type = "danger";
    } else {
        if ($data['daily_count'] < 10) {
            // allow use + increment counter
            $new_count = $data['daily_count'] + 1;
            $sql_update = "UPDATE table_the_iot_projects 
                           SET daily_count=?, last_used=? WHERE id=?";
            $q_update = $pdo->prepare($sql_update);
            $q_update->execute([$new_count, $today, $id]);

            $msg = "✅ Welcome, <b>" . htmlspecialchars($data['name']) . "</b>! 
                    (Usage $new_count / 10 today)";
            $msg_type = "success";

            // insert log
            $sql_log = "INSERT INTO log_records (card_id, name, scan_date) VALUES (?, ?, NOW())";
            $q_log = $pdo->prepare($sql_log);
            $q_log->execute([$id, $data['name']]);

            $data['daily_count'] = $new_count; // update local copy
        } else {
            // lock user
            $sql_lock = "UPDATE table_the_iot_projects SET is_locked=1 WHERE id=?";
            $q_lock = $pdo->prepare($sql_lock);
            $q_lock->execute([$id]);

            $msg = "❌ Max 10 uses reached today! Your ID is now LOCKED.";
            $msg_type = "danger";
            $data['is_locked'] = 1;
        }
    }
} else {
    $msg = "❌ The ID of your Card / KeyChain is not registered!";
    $data = [
        'id' => $id,
        'name' => "--------",
        'gender' => "--------",
        'email' => "--------",
        'mobile' => "--------"
    ];
}

Database::disconnect();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Read Tag - NodeMCU ESP8266 Project</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <!-- Message -->
    <div class="container mt-4">
        <div class="alert alert-<?php echo $msg_type; ?> text-center fw-bold shadow" role="alert" style="font-size:18px;">
            <?php echo $msg; ?>
        </div>

        <!-- User Data -->
        <div class="card shadow mt-4">
            <div class="card-header bg-white text-dark text-center fw-bold">
                User Data
            </div>
            <div class="card-body">
                <table class="table table-striped table-bordered text-center">
                    <tbody>
                        <tr>
                            <td><b>ID</b></td>
                            <td><?php echo $data['id']; ?></td>
                        </tr>
                        <tr>
                            <td><b>Name</b></td>
                            <td><?php echo $data['name']; ?></td>
                        </tr>
                        <tr>
                            <td><b>Gender</b></td>
                            <td><?php echo $data['gender']; ?></td>
                        </tr>
                        <tr>
                            <td><b>Email</b></td>
                            <td><?php echo $data['email']; ?></td>
                        </tr>
                        <tr>
                            <td><b>Mobile</b></td>
                            <td><?php echo $data['mobile']; ?></td>
                        </tr>
                        <?php if (isset($data['daily_count'])): ?>
                        <tr>
                            <td><b>Uses Today</b></td>
                            <td><?php echo $data['daily_count']; ?>/10</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <?php if (isset($data['is_locked']) && $data['is_locked'] == 1): ?>
                <div class="alert alert-danger text-center fw-bold mt-3">
                    🔒 CARD IS LOCKED
                </div>
                <div class="text-center mt-2">
                    <a href="unlock.php?id=<?php echo $data['id']; ?>" class="btn btn-warning btn-lg">
                        🔓 Unlock Card
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Auto redirect after 5s -->
    <script>
        setTimeout(function() {
            window.location.href = "home.php";
        }, 5000);
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
