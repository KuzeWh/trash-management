<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include 'db.php';

$userId = $_SESSION['user_id'];
$userRole = $_SESSION['role'] ?? '';

// Validasi dan sanitasi input tanggal
$filter = "";
$start_date = $end_date = '';
if (isset($_GET['start_date'], $_GET['end_date'])) {
    $start_date = $_GET['start_date'];
    $end_date = $_GET['end_date'];
    
    // Periksa format tanggal
    if (DateTime::createFromFormat('Y-m-d', $start_date) && DateTime::createFromFormat('Y-m-d', $end_date)) {
        $filter = "AND tanggal BETWEEN ? AND ?";
    } else {
        echo "Format tanggal tidak valid!";
        exit();
    }
}

$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$newReportsCount = 0;
if ($userRole === 'admin') {
    $queryLastSeen = "SELECT last_seen_report FROM users WHERE id = ?";
    $stmt = $conn->prepare($queryLastSeen);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $lastSeenReportId = $stmt->get_result()->fetch_assoc()['last_seen_report'] ?? 0;
    $stmt->close();

    $sqlNewReports = "SELECT COUNT(id) AS new_reports FROM laporan_sampah WHERE id > ?";
    $stmt = $conn->prepare($sqlNewReports);
    $stmt->bind_param("i", $lastSeenReportId);
    $stmt->execute();
    $newReportsCount = $stmt->get_result()->fetch_assoc()['new_reports'];
    $stmt->close();
}

$sqlCount = "SELECT COUNT(id) AS total FROM laporan_sampah WHERE user_id = ? $filter";
$stmt = $conn->prepare($sqlCount);
if ($filter) {
    $stmt->bind_param("iss", $userId, $start_date, $end_date);
} else {
    $stmt->bind_param("i", $userId);
}
$stmt->execute();
$totalLaporan = $stmt->get_result()->fetch_assoc()['total'];
$totalPages = ceil($totalLaporan / $limit);
$stmt->close();

$sql = "SELECT * FROM laporan_sampah WHERE user_id = ? $filter ORDER BY id DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
if ($filter) {
    $stmt->bind_param("issii", $userId, $start_date, $end_date, $limit, $offset);
} else {
    $stmt->bind_param("iii", $userId, $limit, $offset);
}
$stmt->execute();
$laporan = $stmt->get_result();

$queryUser = "SELECT profile_pic, badge FROM users WHERE id = ?";
$stmt = $conn->prepare($queryUser);
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($userId) {
    $query = "SELECT COUNT(*) AS total_laporan FROM laporan WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $totalLaporan = $stmt->get_result()->fetch_assoc()['total_laporan'];
    $stmt->close();

    $newBadge = 'Newbie';
    if ($totalLaporan >= 5 && $totalLaporan <= 9) $newBadge = 'Recycler';
    elseif ($totalLaporan >= 10) $newBadge = 'Eco Warrior';

    $queryCurrentBadge = "SELECT badge FROM users WHERE id = ?";
    $stmt = $conn->prepare($queryCurrentBadge);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $currentBadge = $stmt->get_result()->fetch_assoc()['badge'];
    $stmt->close();

    if ($newBadge !== $currentBadge) {
        $queryUpdateBadge = "UPDATE users SET badge = ? WHERE id = ?";
        $stmt = $conn->prepare($queryUpdateBadge);
        $stmt->bind_param("si", $newBadge, $userId);
        $stmt->execute();
        $stmt->close();
    }
}

$userId = $_SESSION['user_id'];
$targetLaporan = 10; // Target jumlah laporan untuk mencapai 100%

// Hitung jumlah laporan pengguna
$query = "SELECT COUNT(*) AS total_laporan FROM laporan_sampah WHERE user_id = $userId";
$result = $conn->query($query);
$totalLaporan = $result->fetch_assoc()['total_laporan'];

// Hitung persentase progress
$progress = ($totalLaporan / $targetLaporan) * 100;
$progress = min($progress, 100); // Pastikan tidak melebihi 100%

// Badge berdasarkan jumlah laporan
$badge = 'Newbie';
if ($totalLaporan >= 5 && $totalLaporan <= 9) {
    $badge = 'Recycler';
} elseif ($totalLaporan >= 10) {
    $badge = 'Eco Warrior';
}

$conn->close();
?>



<!-- (HTML structure remains the same) -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootswatch/4.5.2/lux/bootstrap.min.css">
    <style>
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

  /* Sidebar */
  .sidebar {
        height: 100%;
        width: 0;
        position: fixed;
        top: 0;
        left: 0;
        background-color: #343a40; /* Lux Bootstrap dark color */
        overflow-x: hidden;
        transition: 0.4s;
        padding-top: 60px;
        color: #fff;
    }

    .sidebar h4 {
        color:#ffffff;
    }
    
    /* Link styling in the sidebar */
    .sidebar a {
        padding: 10px 15px;
        text-decoration: none;
        font-size: 18px;
        color: #f1f1f1;
        display: block;
        transition: 0.3s;
    }
    
    .sidebar a:hover {
        color: #ffa500;
    }
    
    /* Close button styling */
    .sidebar .close-btn {
        position: absolute;
        top: 10px;
        right: 25px;
        font-size: 36px;
    }

    /* Burger button styling */
    .burger-btn {
        font-size: 30px;
        background-color: transparent;
        border: none;
        color: #343a40;
        cursor: pointer;
        position: fixed;
        top: 15px;
        left: 15px;
    }
</style>

</head>
<body>

    <div class="container mt-5">

        <h2>Dashboard</h2>
        <nav class="mb-4">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahLaporanModal">
                Tambah Laporan
            </button>
            <a href="export_csv.php" class="btn btn-secondary">Export ke CSV</a>
            <a href="export_pdf.php" class="btn btn-secondary">Export ke PDF</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
            <a href="faq.php" class="btn btn-info">FAQ</a>
            <?php if ($userRole === 'admin'): ?>
                <a href="admin_dashboard.php" class="btn btn-warning">Dashboard Admin</a>
            <?php endif; ?>
        </nav>

        <!-- Sidebar -->
        <div id="sidebar" class="sidebar">
            <a href="javascript:void(0)" class="close-btn" onclick="toggleSidebar()">&times;</a>
            <h4>Manajemen Sampah Plastik</h4>
            <img src="<?= htmlspecialchars($user['profile_pic'] ?? 'default.png'); ?>" alt="Foto Profil"
                class="img-thumbnail" width="100" height="100" onclick="openProfileModal()">
            <p>Badge: <strong><?= $badge; ?></strong></p>            <a href="dashboard.php">Dashboard</a>
            <a href="faq.php">FAQ</a>
            <a href="rating.php">Rating</a>
            <a href="testimoni.php">Testimoni</a>
            <a href="logout.php">Keluar</a>
        </div>

        <!-- Burger Menu Button -->
        <button class="burger-btn" onclick="toggleSidebar()">&#9776;</button>
        <button id="darkModeToggle" class="btn btn-secondary mt-3">Toggle Dark Mode</button>

        <div class="container mt-4">
            <h4>Progress Laporan Anda</h4>
            <div class="progress" style="height: 25px;">
                <div class="progress-bar bg-success" role="progressbar" 
                    style="width: <?= $progress; ?>%;" 
                    aria-valuenow="<?= $progress; ?>" aria-valuemin="0" aria-valuemax="100">
                    <?= round($progress); ?>%
                </div>
            </div>
            <p class="mt-2">Anda telah mengunggah <strong><?= $totalLaporan; ?></strong> dari <?= $targetLaporan; ?> laporan. Badge Anda saat ini: <strong><?= $badge; ?></strong>.</p>
        </div>


        <!-- Modal untuk Ganti Foto Profil -->
        <div class="modal fade" id="profileModal" tabindex="-1" role="dialog" aria-labelledby="profileModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="update_profile_pic.php" method="post" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title" id="profileModalLabel">Ganti Foto Profil</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="file" name="profile_pic" class="form-control" required>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <h3>Filter Laporan</h3>
        <form method="GET" class="form-inline mb-4">
            <div class="form-group mr-2">
                <label for="start_date">Dari: </label>
                <input type="date" name="start_date" class="form-control ml-2" required>
            </div>
            <div class="form-group mr-2">
                <label for="end_date">Sampai: </label>
                <input type="date" name="end_date" class="form-control ml-2" required>
            </div>
            <button type="submit" class="btn btn-info">Filter</button>
        </form>

        <h2 class="text-center mb-4">Tabel Laporan Sampah</h2>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Sampah Organik (Kg)</th>
                    <th>Sampah Anorganik (Kg)</th>
                    <th>Sampah Berbahaya (Kg)</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($laporan)) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['sampah_organik']; ?></td>
                        <td><?php echo $row['sampah_anorganik']; ?></td>
                        <td><?php echo $row['sampah_berbahaya']; ?></td>
                        <td><?php echo $row['tanggal']; ?></td>
                        <td>
                            <!-- Tombol Edit memicu modal -->
                            <button class="btn btn-warning btn-sm" onclick="setEditData(<?php echo $row['id']; ?>, <?php echo $row['sampah_organik']; ?>, <?php echo $row['sampah_anorganik']; ?>, <?php echo $row['sampah_berbahaya']; ?>)" data-bs-toggle="modal" data-bs-target="#editLaporanModal">Edit</button>
                            <!-- Tombol Hapus memicu modal konfirmasi -->
                            <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?php echo $row['id']; ?>)" data-bs-toggle="modal" data-bs-target="#hapusLaporanModal">
                                Hapus
                            </button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>&start_date=<?= $start_date ?>&end_date=<?= $end_date ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>

        <a href="grafik.php" class="btn btn-info mt-3">Lihat Grafik</a>

    </div>

    <!-- Modal Tambah Laporan -->
    <div class="modal fade" id="tambahLaporanModal" tabindex="-1" aria-labelledby="tambahLaporanModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahLaporanModalLabel">Tambah Laporan Sampah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">✕</button>
                </div>
                <form action="proses_tambah_laporan.php" method="POST">
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="sampah_organik">Jumlah Sampah Organik (Kg)</label>
                            <input type="number" name="sampah_organik" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="sampah_anorganik">Jumlah Sampah Anorganik (Kg)</label>
                            <input type="number" name="sampah_anorganik" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="sampah_berbahaya">Jumlah Sampah Berbahaya (Kg)</label>
                            <input type="number" name="sampah_berbahaya" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Laporan -->
    <div class="modal fade" id="editLaporanModal" tabindex="-1" aria-labelledby="editLaporanModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editLaporanModalLabel">Edit Laporan Sampah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">✕</button>
                </div>
                <form action="proses_edit_laporan.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="id">
                        
                        <div class="form-group mb-3">
                            <label for="edit_sampah_organik">Jumlah Sampah Organik (Kg)</label>
                            <input type="number" id="edit_sampah_organik" name="sampah_organik" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="edit_sampah_anorganik">Jumlah Sampah Anorganik (Kg)</label>
                            <input type="number" id="edit_sampah_anorganik" name="sampah_anorganik" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="edit_sampah_berbahaya">Jumlah Sampah Berbahaya (Kg)</label>
                            <input type="number" id="edit_sampah_berbahaya" name="sampah_berbahaya" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="hapusLaporanModal" tabindex="-1" aria-labelledby="hapusLaporanModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="hapusLaporanModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">✕</button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus laporan ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Hapus</a>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript untuk Mengisi Data di Modal Edit dan Konfirmasi Hapus -->
    <script>
        function setEditData(id, sampahOrganik, sampahAnorganik, sampahBerbahaya) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_sampah_organik').value = sampahOrganik;
            document.getElementById('edit_sampah_anorganik').value = sampahAnorganik;
            document.getElementById('edit_sampah_berbahaya').value = sampahBerbahaya;
        }

        function confirmDelete(id) {
            document.getElementById('confirmDeleteBtn').setAttribute('href', 'delete_laporan.php?id=' + id);
        }

        function markAsRead() {
        fetch('update_last_seen_report.php', {method: 'POST'})
            .then(response => response.text())
            .then(data => {
                location.reload();
            });
        }

            function openProfileModal() {
            var profileModal = new bootstrap.Modal(document.getElementById('profileModal'));
            profileModal.show();
        }

        function markAsRead() {
            $.ajax({
                url: 'mark_notification_read.php',
                type: 'POST',
                data: { action: 'mark_all_as_read' },
                success: function(response) {
                    alert(response); // Menampilkan pesan dari server
                    location.reload(); // Reload halaman agar notifikasi hilang
                },
                error: function() {
                    alert("Gagal menandai notifikasi sebagai telah dibaca.");
                }
            });
        }

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

        function toggleSidebar() {
        const sidebar = document.getElementById("sidebar");
        if (sidebar.style.width === "250px") {
            sidebar.style.width = "0";
        } else {
            sidebar.style.width = "250px";
        }
    }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
