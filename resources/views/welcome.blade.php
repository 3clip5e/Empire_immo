<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Empire-Immo</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome -->
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

  <style>
    body { transition: background-color 0.3s, color 0.3s; padding-top: 70px; }
    body.dark-mode { background-color: #171515; color: #fff; }
    #theme-toggle { padding: 8px 16px; margin: 10px; border: none; border-radius: 4px; background: #ccc; cursor: pointer; }

    .welcome-section {
      height: 90vh; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; position: relative; overflow: hidden;
    }
    .welcome-section::before {
      content: "";
      position: absolute; top: 0; left: 0; width: 100%; height: 100%;
      background-image: url("background.png.jpg");
      background-size: cover; background-position: center;
      filter: blur(3px) brightness(70%);
      z-index: 1;
    }
    .welcome-section > * { position: relative; z-index: 2; }

    .btn-custom { margin: 10px; width: 150px; }

    .navbar { position: fixed; top: 0; left: 0; width: 100%; z-index: 1000; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
  </style>
</head>
<body>

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
          <li class="nav-item"><a class="nav-link active" href="{{ route('home') }}">Accueil</a></li>
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

  <script>
    if (localStorage.getItem('theme') === 'dark') document.body.classList.add('dark-mode');
    document.getElementById('theme-toggle').addEventListener('click', function() {
      document.body.classList.toggle('dark-mode');
      localStorage.setItem('theme', document.body.classList.contains('dark-mode') ? 'dark' : 'light');
    });
  </script>

  <!-- Welcome Section -->
  <div class="welcome-section">
    <h1 class="fw-bold mb-2">Bienvenue sur Empire-Immo</h1>
    <p class="mb-2">Votre plateforme de confiance pour location immobilière.</p>
    <div>
      <a href="{{ route('login') }}" class="btn btn-warning btn-custom">Se connecter</a>
      <a href="{{ route('register') }}" class="btn btn-warning btn-custom">S'inscrire</a>
    </div>
  </div>

  <!-- Recherche de logement -->
  <section class="container my-5">
    <h3 class="mb-4 text-center">Recherchez un logement</h3>
    <form class="row g-3 justify-content-center" action="{{ route('geo.search') }}" method="GET">
      <div class="col-md-3">
        <select name="type" class="form-select">
          <option selected>Type de bien</option>
          <option>Appartement</option>
          <option>Maison</option>
          <option>Studio</option>
          <option>Chambre</option>
          <option>Villa</option>
        </select>
      </div>
      <div class="col-md-3"><input type="text" name="ville" class="form-control" placeholder="Ville ou quartier"></div>
      <div class="col-md-3"><input type="text" name="budget" class="form-control" placeholder="Budget max (FCFA)"></div>
      <div class="col-md-2"><button type="submit" class="btn btn-secondary w-100">Rechercher</button></div>
    </form>
  </section>

  <section class="position-relative" style="height: 100vh; overflow: hidden;">
    <video autoplay muted loop playsinline
        class="position-absolute w-100 h-100"
        style="object-fit: cover; z-index: -1;">
        <source src="{{ asset('video1.mp4') }}" type="video/mp4">
  
    </video>

</section>

  <!-- Biens en vedette -->
  <section id="properties" class="container my-5">
    <h3 class="mb-4 text-center">Nos biens en vedette</h3>
    <div class="row g-4">
      @foreach($featuredProperties ?? [] as $index => $prop)
      <div class="col-md-4">
        <div class="card h-100 shadow-sm text-center">
          <img src="{{ asset($prop->image) }}" class="card-img-top" alt="{{ $prop->title }}">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">{{ $prop->title }}</h5>
            <p class="card-text">{{ $prop->description }}</p>
            <a href="" class="btn btn-outline-dark mt-auto" data-bs-toggle="modal" data-bs-target="#detailsModal{{ $index }}">Voir détails</a>
          </div>
        </div>
      </div>

      <!-- Modal -->
      <div class="modal fade" id="detailsModal{{ $index }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">{{ $prop->title }}</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <img src="{{ asset($prop->image) }}" class="img-fluid mb-3" alt="{{ $prop->title }}">
              <p><strong>Type :</strong> {{ $prop->type }}</p>
              <p><strong>Description :</strong> {{ $prop->description }}</p>
              <p>Bel logement situé au cœur de la ville, idéal pour tous.</p>
            </div>
            <div class="modal-footer">
              <form action="{{ route('properties.reserve', $prop->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-warning">Réserver</button>
              </form>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </section>

  <section class="container my-5 text-center" id="about">
  <h3 class="mb-4">À propos de Empire-Immo</h3>
  <img src="photo4.png" style="max-width: 600px; margin: 0 auto;">
  <p style="max-width: 600px; margin: 0 auto;">
    Chez Empire-Immo, nous nous engageons à simplifier la recherche et la gestion de logements pour nos utilisateurs. 
    Grâce à notre plateforme innovante, nous mettons à disposition un large choix de biens immobiliers adaptés à chaque besoin, 
    tout en garantissant un service fiable, sécurisé et accessible. Notre équipe passionnée travaille sans relâche pour offrir 
    une expérience utilisateur optimale, facilitant ainsi laccès au logement pour tous.
  </p>
</section>

  <h3 class="mb-4 text-center">Témoignages</h3>
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center fw-bold mb-4">Témoignages de nos clients</h2>
        <div class="row g-4">

            <!-- Témoignage 1 -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 text-center p-4">
                    <img src="{{ asset('photo13.jpg') }}" alt="Jean Dupont" 
                         class="rounded-circle mx-auto d-block mb-3" width="150" height="150">
                    <p class="fst-italic">"Empire-Immo m’a permis de trouver un appartement en moins de 3 jours, sans me déplacer !"</p>
                    <h6 class="fw-bold mb-0">Jean Dupont</h6>
                    <small class="text-muted">Propriétaire</small>
                </div>
            </div>

            <!-- Témoignage 2 -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 text-center p-4">
                    <img src="{{ asset('photo14.jpg') }}" alt="Marie Kamdem" 
                         class="rounded-circle mx-auto d-block mb-3" width="150" height="150">
                    <p class="fst-italic">"Une plateforme simple, rapide et efficace. Je recommande à 100%."</p>
                    <h6 class="fw-bold mb-0">Marie Kamdem</h6>
                    <small class="text-muted">Client satisfait</small>
                </div>
            </div>

            <!-- Témoignage 3 -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 text-center p-4">
                    <img src="{{ asset('photo15.jpg') }}" alt="Paul Nguema" 
                         class="rounded-circle mx-auto d-block mb-3" width="150" height="150">
                    <p class="fst-italic">"Grâce à Empire-Immo, j’ai loué mon logement en moins d’une semaine."</p>
                    <h6 class="fw-bold mb-0">Paul Nguema</h6>
                    <small class="text-muted">Etudiante</small>
                </div>
            </div>

        </div>
    </div>
</section>


<!-- Section Commentaires -->
<section class="container my-5" id="commentaires">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <h3 class="mb-4 text-center">Laissez un commentaire</h3>
      <form>
        <div class="mb-3">
          <label for="nom" class="form-label">Nom</label>
          <input type="text" class="form-control" id="nom" placeholder="Entrez votre nom" required>
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Adresse e-mail</label>
          <input type="email" class="form-control" id="email" placeholder="exemple@email.com" required>
        </div>
        <div class="mb-3">
          <label for="commentaire" class="form-label">Votre commentaire</label>
          <textarea class="form-control" id="commentaire" rows="4" placeholder="Écrivez votre message ici..." required></textarea>
        </div>

        <div class="text-center">
        <a href="/creer_compte">
          <button type="submit" class="btn btn-warning w-3  0">Envoyer</button>
          </a>
        </div>
        
      </form>
    </div>
  </div>
</section>


  <!-- Footer -->
  <footer style="background-color: #333; color: #fff; text-align: center; padding: 20px 0;">
    <div style="display: flex; justify-content: center; gap: 30px; flex-wrap: wrap; margin-bottom: 15px;">
      <div style="display: flex; align-items: center; gap: 8px;"><i class="fab fa-facebook-f" style="color:#3b5998;"></i><span>Facebook</span></div>
      <div style="display: flex; align-items: center; gap: 8px;"><i class="fas fa-envelope" style="color:#dd4b39;"></i><span>Email</span></div>
      <div style="display: flex; align-items: center; gap: 8px;"><i class="fab fa-whatsapp" style="color:#25D366;"></i><span>WhatsApp</span></div>
    </div>
    <p>© 2025 Empire-Immo. Tous droits réservés.</p>
    <p>Contactez-nous : contact@empire-immo.com</p>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
