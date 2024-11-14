<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Sampah Plastik</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootswatch/4.5.2/lux/bootstrap.min.css">
    <style>
        /* Styling untuk Hero Image */
        .hero {
            background-image: url('Mountain_Sketch_upscaled.jpeg'); /* Ganti URL dengan hero image yang sesuai */
            background-size: cover;
            background-position: center;
            height: 80vh;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            flex-direction: column;
        }

        /* Styling untuk tombol Sign Up dan Sign In */
        .btn-group {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        /* Animasi untuk card saat di-hover */
        .card:hover {
            transform: scale(1.05);
            transition: transform 0.3s ease;
        }

        /* Ikon ukuran di card */
        .card-icon {
            font-size: 40px;
            color: #007bff; /* Sesuaikan warna ikon sesuai tema */
            margin-bottom: 15px;
        }

        .hero h1 {
            padding: 10px 10px;
            background-color: black;
            color: white;
            border-radius: 5px;
        }

        .hero p {
            padding: 5px;
            background-color: white;
            color: black;
        }
        /* Mode Gelap untuk seluruh halaman */
        body.dark-mode {
            background-color: #121212;
            color: #ffffff;
        }

        /* Gaya elemen-elemen umum di dark mode */
        .dark-mode .card,
        .dark-mode .navbar,
        .dark-mode .btn,
        .dark-mode .progress {
            background-color: #333;
            color: #ffffff;
        }

        .dark-mode .btn-primary {
            background-color: #bb86fc;
            border-color: #bb86fc;
            color: #ffffff;
        }

        .dark-mode .btn-secondary {
            background-color: #03dac5;
            border-color: #03dac5;
            color: #121212;
        }

        .dark-mode .form-control, 
        .dark-mode .form-control-file, 
        .dark-mode input[type="text"], 
        .dark-mode input[type="email"], 
        .dark-mode input[type="password"], 
        .dark-mode input[type="date"], 
        .dark-mode select, 
        .dark-mode textarea {
            background-color: #333;
            color: #ffffff;
            border-color: #444;
        }

        .dark-mode .form-group label,
        .dark-mode h1, 
        .dark-mode h2, 
        .dark-mode h3, 
        .dark-mode h4, 
        .dark-mode h5, 
        .dark-mode p {
            color: #e0e0e0;
        }

        .dark-mode .table {
            background-color: #333;
            color: #ffffff;
        }

        /* Gaya tambahan untuk teks yang sulit terlihat */
        .dark-mode a,
        .dark-mode .navbar a,
        .dark-mode .card a {
            color: #bb86fc;
        }

        /* Mengubah warna tabel */
        .dark-mode .table th, 
        .dark-mode .table td {
            border-color: #444;
        }

        /* Hover efek untuk dark mode */
        .dark-mode .btn:hover, 
        .dark-mode .card:hover {
            background-color: #444;
        }

        /* Progress bar dalam mode gelap */
        .dark-mode .progress-bar {
            background-color: #03dac5;
        }
    </style>
    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Hero Image Section -->
    <div class="hero">
        <h1 class="display-4">Manajemen Sampah Plastik</h1>
        <p class="lead">Kelola laporan sampah plastik Anda secara efektif.</p>
        <div class="btn-group">
            <a href="signup.php" class="btn btn-primary">Sign Up</a>
            <a href="login.php" class="btn btn-secondary">Sign In</a>
            <a href="faq.php" class="btn btn-info">FAQ</a>
            <button id="darkModeToggle" class="btn btn-secondary mt-3">Toggle Dark Mode</button>
        </div>
    </div>

    <div class="container mt-5 text-center">
        <h2>Tujuan Kami</h2>
        <p>Berikut adalah tiga tujuan utama dari platform ini</p>
    </div>

    <div class="container mt-4">
        <div class="row text-center">
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <div class="card-icon">
                            <i class="fas fa-leaf"></i> <!-- Ikon untuk Mengurangi Polusi -->
                        </div>
                        <h5 class="card-title">Mengurangi Polusi</h5>
                        <p class="card-text">Menyediakan platform untuk mengurangi sampah plastik melalui pengelolaan yang terstruktur dan mudah diakses.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <div class="card-icon">
                            <i class="fas fa-lightbulb"></i> <!-- Ikon untuk Peningkatan Kesadaran -->
                        </div>
                        <h5 class="card-title">Peningkatan Kesadaran</h5>
                        <p class="card-text">Memberikan informasi dan data yang membantu meningkatkan kesadaran masyarakat akan pentingnya pengurangan sampah plastik.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <div class="card-icon">
                            <i class="fas fa-hands-helping"></i> <!-- Ikon untuk Kolaborasi Komunitas -->
                        </div>
                        <h5 class="card-title">Kolaborasi Komunitas</h5>
                        <p class="card-text">Mendorong kolaborasi dan partisipasi komunitas dalam usaha pengelolaan dan daur ulang sampah plastik.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Periksa status dark mode saat halaman dimuat
        document.addEventListener("DOMContentLoaded", function () {
            const darkModeEnabled = localStorage.getItem("darkMode") === "enabled";
            if (darkModeEnabled) {
            document.body.classList.add("dark-mode");
            }

            // Toggle dark mode saat tombol diklik
            document.getElementById("darkModeToggle").addEventListener("click", function () {
            document.body.classList.toggle("dark-mode");
            
            // Simpan status ke localStorage
            if (document.body.classList.contains("dark-mode")) {
                localStorage.setItem("darkMode", "enabled");
            } else {
                localStorage.setItem("darkMode", "disabled");
            }
            });
        });
    </script>
</body>
</html>
