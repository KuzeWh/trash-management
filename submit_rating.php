<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
$feedback = isset($_POST['feedback']) ? $_POST['feedback'] : '';

if ($rating >= 1 && $rating <= 5) {
    $stmt = $conn->prepare("INSERT INTO ratings (user_id, rating, feedback) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $user_id, $rating, $feedback);
    if ($stmt->execute()) {
        echo "Terima kasih atas rating dan feedback Anda!";
    } else {
        echo "Terjadi kesalahan saat menyimpan data. Silakan coba lagi.";
    }
    $stmt->close();
} else {
    echo "Rating tidak valid.";
}

$conn->close();
header("Location: rating.php"); // Redirect setelah submit
exit();
?>
