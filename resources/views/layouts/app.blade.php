<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Empire-Immo</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <style>
        body { transition: background-color 0.3s, color 0.3s; padding-top: 70px; }
        body.dark-mode { background-color: #171515; color: #fff; }
        #theme-toggle { padding: 8px 16px; margin-left:10px; border: none; border-radius: 4px; background: #ccc; cursor: pointer; }
        .navbar { position: fixed; top:0; left:0; width:100%; z-index:1000; box-shadow:0 2px 5px rgba(0,0,0,0.1);}
        main { padding-top:80px; min-height: 80vh; }
        footer { background-color: #333; color: #fff; text-align: center; padding: 20px 0; margin-top:40px; }
        footer .socials { display: flex; justify-content: center; gap: 30px; flex-wrap: wrap; margin-bottom: 15px; }
        footer .socials div { display: flex; align-items: center; gap: 8px; cursor: pointer; }
    </style>
</head>
<body class="{{ session('theme','') === 'dark' ? 'dark-mode' : '' }}">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-warning">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('home') }}">
            <img src="{{ asset('logo.png') }}" alt="Logo Empire-Immo" width="40" height="40" class="me-2">
            Empire-Immo
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="/apropos">À propos</a></li>
                <li class="nav-item"><a class="nav-link" href="/contact">Contact</a></li>
            </ul>

            @guest
                <a href="{{ route('login') }}" class="btn btn-outline-dark btn-sm me-2">Connexion</a>
                <a href="{{ route('register') }}" class="btn btn-dark btn-sm">S'inscrire</a>
            @else
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-dark btn-sm">Déconnexion</button>
                </form>
            @endguest

            <button id="theme-toggle">Changer mode</button>
        </div>
    </div>
</nav>

<!-- Main -->
<main class="container">
    @if(session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger mt-3">{{ $errors->first() }}</div>
    @endif

    @yield('content')
</main>

<!-- Footer -->
<footer>
    <div class="socials">
        <div><i class="fab fa-facebook-f" style="color:#3b5998;"></i><span>Facebook</span></div>
        <div><i class="fas fa-envelope" style="color:#dd4b39;"></i><span>Email</span></div>
        <div><i class="fab fa-whatsapp" style="color:#25D366;"></i><span>WhatsApp</span></div>
    </div>
    <p>© 2025 Empire-Immo. Tous droits réservés.</p>
    <p>Contactez-nous : contact@empire-immo.com</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Theme toggle
    if (localStorage.getItem('theme') === 'dark') document.body.classList.add('dark-mode');
    document.getElementById('theme-toggle').addEventListener('click', function() {
        document.body.classList.toggle('dark-mode');
        localStorage.setItem('theme', document.body.classList.contains('dark-mode') ? 'dark' : 'light');
    });
</script>

</body>
</html>
