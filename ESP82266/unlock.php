

<?php
require 'database.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Reset locked status and daily count
    $sql = "UPDATE table_the_iot_projects 
            SET is_locked = 0, daily_count = 0 
            WHERE id = ?";
    $q = $pdo->prepare($sql);
    $q->execute([$id]);

    Database::disconnect();

    // Redirect back with success message
    header("Location: user data.php?msg=unlocked");
    exit();
} else {
    echo "No ID specified.";
}
?>

