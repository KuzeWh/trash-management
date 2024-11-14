<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testimoni Pengguna</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootswatch/4.5.2/lux/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Testimoni Pengguna</h2>
        <a href="dashboard.php" class="btn btn-primary mb-4">Kembali ke Dashboard User</a>
        <div class="row">
            <?php
            include 'db.php';
            $result = $conn->query("SELECT u.username, r.rating, r.feedback, r.created_at FROM ratings r JOIN users u ON r.user_id = u.id ORDER BY r.created_at DESC");

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='col-md-4 mb-4'>";
                    echo "<div class='card'>";
                    echo "<div class='card-body'>";
                    echo "<h5 class='card-title'>" . htmlspecialchars($row['username']) . "</h5>";
                    echo "<p class='card-text'><strong>Rating:</strong> " . htmlspecialchars($row['rating']) . "/5</p>";
                    echo "<p class='card-text'>" . htmlspecialchars($row['feedback']) . "</p>";
                    echo "<p class='card-text'><small class='text-muted'>Diberikan pada " . htmlspecialchars($row['created_at']) . "</small></p>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<p>Belum ada testimoni.</p>";
            }
            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>
