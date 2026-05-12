<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SiKoprasi Duwe - Koperasi Pura Pande Majepahit</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .landing-nav {
            background: #fff;
            border-bottom: 1px solid #ebe9f1;
            padding: .75rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
        }
        .landing-nav .brand {
            font-weight: 700;
            font-size: 1.15rem;
            color: #5e5873;
            text-decoration: none;
        }
        .landing-nav .brand span {
            color: #7367f0;
        }
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 6rem 1.5rem 4rem;
            background: linear-gradient(135deg, #f8f7fa 0%, #edeaff 100%);
        }
        .hero h1 {
            font-size: 2.5rem;
            font-weight: 800;
            color: #5e5873;
            margin-bottom: .75rem;
        }
        .hero h1 span {
            color: #7367f0;
        }
        .hero p {
            font-size: 1.1rem;
            color: #b9b9c3;
            max-width: 540px;
            margin: 0 auto 2rem;
        }
        .hero .btn-hero {
            background: #7367f0;
            border: none;
            color: #fff;
            padding: .75rem 2.5rem;
            border-radius: .5rem;
            font-weight: 600;
            font-size: 1rem;
            text-decoration: none;
            display: inline-block;
            transition: all .15s;
        }
        .hero .btn-hero:hover {
            background: #5e50ee;
            transform: translateY(-1px);
        }
        .hero .btn-outline-hero {
            background: none;
            border: 2px solid #7367f0;
            color: #7367f0;
            padding: .7rem 2.5rem;
            border-radius: .5rem;
            font-weight: 600;
            font-size: 1rem;
            text-decoration: none;
            display: inline-block;
            margin-left: .75rem;
            transition: all .15s;
        }
        .hero .btn-outline-hero:hover {
            background: #7367f0;
            color: #fff;
        }
        .section {
            padding: 4rem 1.5rem;
        }
        .section h2 {
            font-weight: 700;
            color: #5e5873;
            margin-bottom: .5rem;
        }
        .section .subtitle {
            color: #b9b9c3;
            margin-bottom: 2.5rem;
        }
        .feature-card {
            padding: 2rem;
            border-radius: .75rem;
            background: #fff;
            box-shadow: 0 4px 24px 0 rgba(34,41,47,.08);
            text-align: center;
            height: 100%;
            transition: transform .2s;
        }
        .feature-card:hover {
            transform: translateY(-3px);
        }
        .feature-card .icon {
            width: 56px;
            height: 56px;
            border-radius: .75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
        }
        .feature-card h5 {
            font-weight: 600;
            color: #5e5873;
        }
        .feature-card p {
            color: #b9b9c3;
            font-size: .9rem;
            margin: 0;
        }
        .footer {
            background: #2f3349;
            color: rgba(255,255,255,.65);
            text-align: center;
            padding: 2rem 1.5rem;
            font-size: .9rem;
        }
        .footer strong {
            color: #fff;
        }
    </style>
</head>
<body>
    <nav class="landing-nav">
        <a href="/" class="brand">Si<span>Koprasi</span> Duwe</a>
        <div>
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-primary btn-sm">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Log in</a>
                @endauth
            @endif
        </div>
    </nav>

    <section class="hero">
        <div>
            <h1>Koperasi <span>Pura Pande</span> Majepahit</h1>
            <p>Bersama membangun kemandirian ekonomi melalui semangat gotong royong dan profesionalisme untuk kesejahteraan seluruh anggota.</p>
            <div>
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-hero">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn-hero">Mulai Sekarang</a>
                @endauth
            </div>
        </div>
    </section>

    <section class="section bg-white">
        <div class="container text-center">
            <h2>Mengapa Bergabung?</h2>
            <p class="subtitle">Nikmati berbagai manfaat sebagai anggota koperasi kami</p>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="icon" style="background:#7367f01a;color:#7367f0;"><i class="bi bi-piggy-bank"></i></div>
                        <h5>Simpanan</h5>
                        <p>Kelola simpanan pokok, wajib, dan sukarela dengan aman dan transparan.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="icon" style="background:#28c76f1a;color:#28c76f;"><i class="bi bi-graph-up-arrow"></i></div>
                        <h5>Pinjaman</h5>
                        <p>Akses pinjaman dengan bunga ringan dan proses cepat untuk anggota.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="icon" style="background:#ff9f431a;color:#ff9f43;"><i class="bi bi-people"></i></div>
                        <h5>Kebersamaan</h5>
                        <p>Menjalin silaturahmi dan kerjasama antar sesama anggota koperasi.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section" style="background:#f8f7fa;">
        <div class="container text-center">
            <h2>Tentang Kami</h2>
            <p class="subtitle">Koperasi Pura Pande Majepahit</p>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <p style="color:#6e6b7b;line-height:1.8;">
                        Koperasi Pura Pande Majepahit adalah koperasi yang berkomitmen untuk meningkatkan kesejahteraan anggota melalui layanan simpan pinjam dan pemberdayaan ekonomi. Berdiri dengan semangat kekeluargaan, kami melayani kebutuhan finansial anggota secara profesional, transparan, dan berkeadilan.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} <strong>SiKoprasi Duwe</strong> — Koperasi Pura Pande Majepahit. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
