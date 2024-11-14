<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berikan Rating</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootswatch/4.5.2/lux/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Beri Rating Website Kami</h2>
        <a href="dashboard.php" class="btn btn-primary mb-4">Kembali ke Dashboard User</a>
        <form action="submit_rating.php" method="POST">
            <div class="form-group">
                <label>Rating (1-5):</label>
                <select name="rating" class="form-control" required>
                    <option value="1">1 - Sangat Buruk</option>
                    <option value="2">2 - Buruk</option>
                    <option value="3">3 - Cukup</option>
                    <option value="4">4 - Baik</option>
                    <option value="5">5 - Sangat Baik</option>
                </select>
            </div>
            <div class="form-group">
                <label>Feedback:</label>
                <textarea name="feedback" class="form-control" rows="4" placeholder="Tulis feedback Anda"></textarea>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Kirim Rating</button>
        </form>
    </div>
</body>
</html>
