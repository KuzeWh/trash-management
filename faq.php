<!-- faq.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - Manajemen Sampah Plastik</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootswatch/4.5.2/lux/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Frequently Asked Questions (FAQ)</h2>

        <div class="accordion" id="faqAccordion">
            <!-- FAQ 1 -->
            <div class="card">
                <div class="card-header" id="headingOne">
                    <h2 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Apa tujuan dari aplikasi Manajemen Sampah Plastik ini?
                        </button>
                    </h2>
                </div>

                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#faqAccordion">
                    <div class="card-body">
                        Aplikasi ini bertujuan untuk membantu pengguna dalam melaporkan dan melacak manajemen sampah plastik yang mereka hasilkan, sekaligus mendorong kebiasaan pengelolaan sampah yang bertanggung jawab.
                    </div>
                </div>
            </div>

            <!-- FAQ 2 -->
            <div class="card">
                <div class="card-header" id="headingTwo">
                    <h2 class="mb-0">
                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Bagaimana cara mendapatkan badge atau peringkat dalam aplikasi ini?
                        </button>
                    </h2>
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#faqAccordion">
                    <div class="card-body">
                        Anda akan mendapatkan badge berdasarkan jumlah laporan yang dikirimkan. Setiap peringkat badge menunjukkan level kontribusi Anda dalam pengelolaan sampah.
                    </div>
                </div>
            </div>

            <!-- FAQ 3 -->
            <div class="card">
                <div class="card-header" id="headingThree">
                    <h2 class="mb-0">
                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Apakah data saya aman di aplikasi ini?
                        </button>
                    </h2>
                </div>
                <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#faqAccordion">
                    <div class="card-body">
                        Kami berkomitmen menjaga privasi dan keamanan data Anda. Semua informasi disimpan dengan aman dan hanya dapat diakses oleh pihak yang berwenang.
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <a href="index.php" class="btn btn-secondary">Kembali ke Beranda</a>
            <a href="dashboard.php" class="btn btn-primary">Kembali ke dashboard</a>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
