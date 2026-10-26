
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kost logic</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fb;
            color: #243047;
        }

        .navbar {
            background-color: #243b64;
        }

        .navbar-brand {
            letter-spacing: 0.5px;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8);
        }

        .nav-link:hover {
            color: #ffffff;
        }

        .welcome-card {
            background: linear-gradient(135deg, #243b64, #4975b8);
            color: white;
            border: none;
            border-radius: 18px;
        }

        .summary-card {
            border: none;
            border-radius: 16px;
            transition: transform 0.2s;
        }

        .summary-card:hover {
            transform: translateY(-4px);
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">
            Kos Logic
        </a>

        <button class="navbar-toggler" type="button"
            data-bs-toggle="collapse" data-bs-target="#navbarMenu"
            aria-controls="navbarMenu" aria-expanded="false"
            aria-label="Buka navigasi">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMenu">
            <div class="navbar-nav ms-auto">
                <a class="nav-link active" href="index.php">Dashboard</a>
                <a class="nav-link" href="kamar_tampil.php">Kamar</a>
                <a class="nav-link" href="penghuni_tampil.php">Penghuni</a>
                <a class="nav-link" href="pembayaran_tampil.php">Pembayaran</a>
            </div>
        </div>
    </div>
</nav>

<main class="container py-4 py-md-5">
