<?php
// dashboard.php
session_start();
if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
  header('Location: login.php');
  exit();
}
$usuario = $_SESSION['usuario'];

$perfil_data = [];
if (file_exists('perfil.json')) {
  $perfil_data = json_decode(file_get_contents('perfil.json'), true);
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Portal Interactivo</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;500;700&display=swap" rel="stylesheet" />
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Roboto', sans-serif;
    }

    body {
      padding-top: 38px;
      background-color: rgb(11, 11, 11);
      color: #fff;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      overflow-x: hidden;
    }

    @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@600&display=swap');

    header {
      position: fixed;
      top: 0;
      width: 100%;
      z-index: 1000;
      padding: 10.3px 60px;
      display: flex;
      justify-content: center;
      align-items: center;
      background: rgba(15, 15, 15, 0.97);
      backdrop-filter: blur(12px);
      border-bottom: 1px solid rgba(255, 255, 255, 0.05);
      box-shadow: 0 10px 25px rgba(0, 255, 255, 0.05);
    }

    header h2 {
      position: absolute;
      left: 40px;
      color: #00e1ff;
      font-size: 1.7rem;
      letter-spacing: 1px;
      text-shadow: 0 0 15px #00e1ff;
      font-family: 'Playfair Display', serif;
    }

    nav {
      display: flex;
      gap: 15px;
      position: relative;
    }

    nav a {
      position: relative;
      color: #ffffff;
      text-decoration: none;
      font-weight: 600;
      font-size: 1rem;
      padding: 5px 4px;
      transition: all 0.4s ease;
      font-family: 'Playfair Display', serif;
    }

    nav a::before {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      width: 0%;
      height: 3px;
      background: linear-gradient(90deg, #00e1ff, #0088ff);
      transition: 0.4s ease;
      transform: translateX(-50%);
    }

    nav a:hover::before {
      width: 100%;
    }

    nav a:hover {
      color: #00e1ff;
      text-shadow: 0 0 6px #00e1ff;
    }

    .nav-item {
      position: relative;
    }

    .dropdown {
      position: absolute;
      top: 30.5px;
      left: 50%;
      transform: translateX(-50%) translateY(-10px);
      background: rgba(15, 15, 15, 0.97);
      border-bottom-right-radius: 14px;
      border-bottom-left-radius: 14px;
      padding: 18px 24px;
      box-shadow: 0 12px 40px rgba(0, 255, 255, 0.25);
      min-width: 900px;
      display: none;
      opacity: 0;
      transition: all 0.4s ease;
      pointer-events: none;
    }

    .dropdown.active {
      display: block;
      opacity: 1;
      transform: translateX(-50%) translateY(0);
      pointer-events: auto;
      animation: slideFade 0.4s ease;
    }

    @keyframes slideFade {
      0% {
        opacity: 0;
        transform: translateX(-50%) translateY(-15px) scale(0.95);
      }

      100% {
        opacity: 1;
        transform: translateX(-50%) translateY(0) scale(1);
      }
    }

    .dropdown a {
      display: block;
      color: #eee;
      margin: 10px 0;
      transition: all 0.3s ease;
      border-radius: 6px;
      padding: 6px 10px;
    }

    .dropdown a:hover {
      background-color: rgba(0, 225, 255, 0.1);
      color: #00e1ff;
      text-shadow: 0 0 6px #00e1ff;
      transform: translateX(6px);
    }

    .hamburger {
      display: none;
      font-size: 1.5rem;
      background: none;
      border: none;
      color: #00e1ff;
      cursor: pointer;
      position: absolute;
      right: 20px;
      top: 15px;
      z-index: 1100;
    }

    @media (max-width: 768px) {
      header {
        flex-direction: column;
        align-items: flex-start;
        padding: 10px 20px;
      }

      header h2 {
        font-size: 1.3rem;
        position: static;
        margin-bottom: 10px;
      }

      nav {
        display: none;
        flex-direction: column;
        width: 100%;
        gap: 0;
      }

      nav.active {
        display: flex;
      }

      .hamburger {
        display: block;
      }

      .dropdown {
        position: static;
        transform: none;
        min-width: 100%;
        box-shadow: none;
        border-radius: 0;
        padding: 10px;
      }

      .dropdown a {
        padding: 8px;
      }
    }



    .video-banner {
      position: relative;
      width: 100%;
      height: 90vh;
      overflow: hidden;
      z-index: 0;
    }

    .video-banner video {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    /* Logo en la esquina */
    .video-logo {
      position: absolute;
      top: 20px;
      left: 30px;
      z-index: 2;
    }

    .video-logo img {
      width: 120px;
      height: auto;
      filter: drop-shadow(0 0 10px rgba(0, 0, 0, 0.6));
    }

    /* Overlay con fondo oscuro */
    .video-overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.55);
      display: flex;
      justify-content: center;
      align-items: center;
      text-align: center;
      z-index: 1;
      padding: 0 20px;
    }

    .overlay-content {
      color: #ffffff;
      animation: fadeInUp 1.5s ease-out forwards;
      max-width: 800px;
    }

    .overlay-content h1 {
      font-size: 3.5rem;
      font-family: 'Playfair Display', serif;
      font-weight: 700;
      text-shadow: 2px 2px 12px rgba(0, 0, 0, 0.8);
      margin-bottom: 0.5rem;
    }

    .overlay-content .typed-text {
      font-size: 1.5rem;
      font-weight: 300;
      color: #ccc;
      margin-bottom: 2rem;
      min-height: 2em;
      /* para que no se mueva al cambiar */
    }

    .cta-button {
      display: inline-block;
      padding: 12px 30px;
      background-color: #00d4ff;
      color: #000;
      text-decoration: none;
      font-weight: bold;
      border-radius: 30px;
      box-shadow: 0 4px 12px rgba(0, 212, 255, 0.3);
      transition: background-color 0.3s ease, transform 0.3s ease;
    }

    .cta-button:hover {
      background-color: #00a3c4;
      transform: translateY(-3px);
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(40px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Responsivo */
    @media (max-width: 768px) {
      .overlay-content h1 {
        font-size: 2rem;
      }

      .overlay-content .typed-text {
        font-size: 1rem;
      }

      .cta-button {
        padding: 10px 20px;
        font-size: 0.9rem;
      }

      .video-logo img {
        width: 90px;
      }
    }

    .typed-text {
      min-height: 2.2em;
      font-family: 'Courier New', monospace;
      /* Estilo de máquina de escribir */
      white-space: nowrap;
      overflow: hidden;
      border-right: 2px solid #00d4ff;
      animation: blinkCursor 0.8s infinite;
    }

    @keyframes blinkCursor {

      0%,
      100% {
        border-color: #00d4ff;
      }

      50% {
        border-color: transparent;
      }
    }

    /* Carrusel */
    .hero-carousel {
      position: relative;
      width: 100%;
      height: 80vh;
      max-height: 800px;
      overflow: hidden;
    }

    .carousel-wrapper {
      width: 100%;
      height: 100%;
      overflow: visible;
      display: flex;
      justify-content: center;
      position: relative;
    }

    .carousel-container {
      display: flex;
      transition: transform 0.5s ease;
      height: 100%;
      width: max-content;
      gap: 20px;
      padding: 0 10%;
    }

    .slide {
      flex: 0 0 80%;
      height: 100%;
      position: relative;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.4);
      transition: all 0.7s ease;
      opacity: 0.5s ease;
      transform: scale(0.85) rotateY(5deg);

    }

    .slide img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      object-position: center;
      display: block;

    }

    .slide.active {
      transform: scale(1) rotateY(0deg);
      z-index: 2;
      filter: none;
      opacity: 1;
      transition: all 0.7s ease;
    }

    .slide.clone {
      transform: scale(0.85);
      filter: blur(1.5px) brightness(0.7);
      transition: all 0.7s ease;
    }

    .slide:not(.active) {
      transform: scale(0.85);
      z-index: 1;
      filter: blur(1.5px) brightness(0.7);
      transition: all 0.7s ease;

    }

    .caption {
      position: absolute;
      top: 40px;
      left: 50%;
      transform: translateX(-50%);
      color: #fff;
      font-size: 3.5rem;
      font-weight: bold;
      padding: 10px 10px;
      border-radius: 10px;
      text-shadow: 2px 2px 4px #000;
      font-family: 'Playfair Display', serif;
    }

    .arrow {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      background-color: rgba(0, 0, 0, 0.5);
      border: none;
      color: #00d4ff;
      font-size: 2rem;
      padding: 10px 20px;
      cursor: pointer;
      z-index: 10;
      border-radius: 50%;
      transition: background-color 0.3s ease;
    }

    .arrow:hover {
      background-color: rgba(0, 0, 0, 0.8);
    }

    @media (max-width: 768px) {
      .caption {
        font-size: 1.2rem;
        padding: 6px 12px;
        bottom: 20px;
      }

      .arrow {
        font-size: 1.5rem;
        padding: 6px 14px;
      }
    }

    .prev {
      left: 20px;
    }

    .next {
      right: 20px;
    }

    .dots {
      text-align: center;
      position: absolute;
      bottom: 15px;
      width: 100%;
      z-index: 10;
    }

    .dot {
      display: inline-block;
      width: 14px;
      height: 14px;
      margin: 0 6px;
      background-color: rgba(255, 255, 255, 0.5);
      border-radius: 50%;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .dot.active,
    .dot:hover {
      background-color: #00d4ff;
    }

    .options {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      margin: 18px auto;
      max-width: 1400px;
      padding: 0 20px;
    }

    .card {
      background-color: rgba(11, 11, 11, 0.44);
      border-radius: 15px;
      padding: 12.5px;
      text-align: center;
      transition: transform 0.3s ease, background-color 0.3s ease;
      cursor: pointer;
      color: #00d4ff;
    }

    .card:hover {
      transform: scale(1.05);
      background-color: rgba(0, 213, 255, 0.87);
      color: rgb(255, 255, 255);
    }

    .card a {
      text-decoration: none;
      font-size: 1.5rem;
      font-weight: 600;
      font-family: 'Playfair Display', serif;
    }

    .catalogo {
      margin: 0 auto;
      overflow: hidden;
      max-width: 100%;
    }

    .catalogo-carousel {
      position: relative;
      width: 100%;
      height: 195px;
      overflow: hidden;
    }

    .catalogo-track {
      display: flex;
      animation: scrollCatalogo 30s linear infinite;
    }

    .catalogo-item {
      flex: 0 0 auto;
      width: 190px;
      height: 160px;
      margin: 25px 10px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .catalogo-item img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }

    /* Efecto al pasar el mouse */
    .catalogo-item:hover {
      transform: translateY(-10px) scale(1.05);
      box-shadow: 0 8px 20px rgba(0, 212, 255, 0.8);
      z-index: 5;
    }

    /* Detener animación al pasar el mouse */
    .catalogo-track:hover {
      animation-play-state: paused;
    }

    /* Animación infinita */
    @keyframes scrollCatalogo {
      0% {
        transform: translateX(0);
      }

      100% {
        transform: translateX(-139%);
      }
    }

    footer {
      background-color: rgb(0, 0, 0);
      text-align: center;
      padding: 10px;
      font-size: 0.9rem;
      color: #ccc;
    }

    /* Sidebar perfil */
    #sidebar {
      position: fixed;
      top: 39px;
      right: 110%;
      width: 350px;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.9);
      backdrop-filter: blur(10px);
      box-shadow: -2px 0 10px rgba(0, 212, 255, 0.2);
      color: #fff;
      padding: 30px 25px;
      transition: right 0.4s ease;
      z-index: 999;
      overflow-y: auto;
      border-right: 2px solid #00d4ff;
      border-top-right-radius: 15px;
      border-bottom-right-radius: 15px;
    }

    #sidebar.active {
      right: 77.5%;
    }

    #sidebar h3 {
      margin-bottom: 20px;
      font-size: 1.8rem;
      color: #00d4ff;
      text-align: center;
    }

    #closeSidebar {
      position: absolute;
      top: 10px;
      left: 10px;
      background: transparent;
      color: #00d4ff;
      border: none;
      font-size: 20px;
      cursor: pointer;
      width: 20%;
    }

    #sidebar p {
      margin-bottom: 10px;
      font-size: 1rem;
    }

    #sidebar label {
      display: block;
      margin-top: 10px;
      font-weight: 500;
      font-size: 0.95rem;
      color: #00d4ff;
    }

    #sidebar input[type="text"],
    #sidebar input[type="email"],
    #sidebar input[type="date"],
    #sidebar textarea,
    #sidebar input[type="file"] {
      width: 100%;
      padding: 8px 10px;
      margin-top: 5px;
      border: 1px solid #00d4ff;
      border-radius: 6px;
      background-color: rgba(255, 255, 255, 0.05);
      color: #fff;
      font-size: 0.95rem;
      outline: none;
    }

    #sidebar textarea {
      resize: vertical;
    }

    #editar {
      margin-top: 15px;
      background-color: #00d4ff;
      border: none;
      padding: 10px 20px;
      border-radius: 8px;
      color: #000;
      font-weight: bold;
      cursor: pointer;
      width: 100%;
    }

    #editar:hover {
      background-color: #00ffff;
    }
  </style>
</head>

<body>
  <header>
    <h2>McLaren A.T.</h2>
    <button class="hamburger" id="hamburger-btn" aria-label="Abrir menú">☰</button>
    <nav>
      <div class="nav-item" data-target="menu1">
        <a href="#" class="sent">Inicio</a>
        <div class="dropdown" id="menu1">
          <a href="inicio.php">Panel Principal</a>
          <a href="#">Estadísticas</a>
        </div>
      </div>
      <div class="nav-item" data-target="menu2">
        <a href="#" class="sent">Ajustes</a>
        <div class="dropdown" id="menu2">
          <a href="ajustes.php">Perfil</a>
          <a href="#">Seguridad</a>
        </div>
      </div>
      <div class="nav-item" data-target="menu3">
        <a href="#" class="sent">Contacto</a>
        <div class="dropdown" id="menu3">
          <a href="contacto.php">Formulario</a>
          <a href="#">Redes Sociales</a>
        </div>
      </div>
      <div class="nav-item" data-target="menu4">
        <a href="#" class="sent">Ayuda</a>
        <div class="dropdown" id="menu4">
          <a href="ayuda.php">FAQ</a>
          <a href="#">Soporte Técnico</a>
        </div>
      </div>
    </nav>
  </header>

  <section class="video-banner">
    <video autoplay muted loop playsinline aria-hidden="true" poster="fallback.jpg">
      <source src="uploads/McLaren 720s Super Series with Ayrton & Bruno Senna - Raise your Limits_Full-HD.mp4"
        type="video/mp4" />
      Tu navegador no soporta el video HTML5.
    </video>

    <!-- Logo -->
    <div class="video-logo">
      <img src="uploads/logo-mclaren.png" alt="McLaren Logo" />
    </div>

    <!-- Contenido central -->
    <div class="video-overlay">
      <div class="overlay-content">
        <h1>Bienvenido al Mundo McLaren</h1>
        <p class="typed-text"></p>
        <a href="#explorar" class="cta-button">Descubre más</a>
      </div>
    </div>
  </section>

  </section>
  <div class="options">
    <div class="card" onclick="abrirPerfil()"><a>Mi Perfil</a></div>
    <div class="card" onclick="window.location.href='noticias.php'"><a>Noticias</a></div>
    <div class="card" onclick="window.location.href='foro.php'"><a>Foro</a></div>
    <div class="card" onclick="window.location.href='galeria.php'"><a>Galería</a></div>
    <div class="card" onclick="window.location.href='configuracion.php'"><a>Configuración</a></div>
    <div class="card" onclick="window.location.href='soporte.php'"><a>Soporte Técnico</a></div>
  </div>

  <!-- Carrusel dinámico -->
  <div class="hero-carousel">
    <div class="carousel-wrapper">
      <div class="carousel-container" id="carousel">
        <!-- Slide duplicado del final (último) -->
        <div class="slide clone"><img src="uploads/solus.jpg" alt="Imagen 4">
          <div class="caption">McLaren Solus GT</div>
        </div>

        <!-- Slides reales -->
        <div class="slide"><img src="uploads/1_mclaren_p1s.jpg" alt="Imagen 1">
          <div class="caption">McLaren P1s</div>
        </div>
        <div class="slide"><img src="uploads/R.jpg" alt="Imagen 2">
          <div class="caption">McLaren</div>
        </div>
        <div class="slide"><img src="uploads/McLaren-750S-2024-25.jpeg" alt="Imagen 3">
          <div class="caption">McLaren 750s</div>
        </div>
        <div class="slide"><img src="uploads/solus.jpg" alt="Imagen 4">
          <div class="caption">McLaren Solus GT</div>
        </div>

        <!-- Slide duplicado del inicio (primero) -->
        <div class="slide clone"><img src="uploads/1_mclaren_p1s.jpg" alt="Imagen 1">
          <div class="caption">McLaren P1s</div>
        </div>
      </div>

    </div>

    <button class="arrow prev" onclick="moverSlide(-1)">&#10094;</button>
    <button class="arrow next" onclick="moverSlide(1)">&#10095;</button>

    <div class="dots" id="dots">
      <span class="dot active" onclick="irASlide(0)"></span>
      <span class="dot" onclick="irASlide(1)"></span>
      <span class="dot" onclick="irASlide(2)"></span>
      <span class="dot" onclick="irASlide(3)"></span>
    </div>
  </div>

  <div class="catalogo">
    <div class="catalogo-carousel">
      <div class="catalogo-track">
        <!-- Repetimos las imágenes para que el loop parezca infinito -->
        <?php
        $imagenes = [
          'uploads/mclaren-senna-sempre.jpg',
          'uploads/McLaren-Senna-2.jpg',
          'uploads/McLaren-MP4-12C_Spider_02_0.webp',
          'uploads/mclaren-1-659412bd504ab.avif',
          'uploads/McLaren_P1_06.jpg',
          'uploads/McLaren_F1_01.jpg',
          'uploads/mclaren_750s.jpg',
          'uploads/mcl60-soymotor.jpg',
          'uploads/Gemballa-McLaren-P1-2014-01.jpg',
          'uploads/2025-mclaren-w1.jpg'
        ];
        // Repetimos las imágenes para que el loop sea más largo
        for ($i = 0; $i < 2; $i++) {
          foreach ($imagenes as $img) {
            echo "<div class='catalogo-item'><img src='$img' alt='Catálogo'></div>";
          }
        }
        ?>
      </div>
    </div>
  </div>

  <footer>
    &copy; 2025 Mi Portal Web. Todos los derechos reservados.
  </footer>

  <!-- Sidebar perfil -->
  <div id="sidebar">
    <button id="closeSidebar" onclick="cerrarPerfil()">Cerrar</button>
    <h3>Mi Perfil</h3>
    <div id="vistaPerfil">



      <div style="text-align: center; margin-bottom: 20px;">
        <img src="<?php echo $perfil_data['foto'] ?? 'https://via.placeholder.com/100'; ?>" alt="Foto de perfil"
          style="border-radius: 50%; border: 2px solid #00d4ff; width: 90%;">
      </div>
      <p><strong>Nombre:</strong> <br><span
          id="nombrePerfil"><?php echo $perfil_data['nombre'] ?? 'Juan Pérez'; ?></span></p>
      <p><strong>Email:</strong> <br><span
          id="emailPerfil"><?php echo $perfil_data['email'] ?? 'juan@example.com'; ?></span></p>
      <p><strong>Teléfono:</strong> <br><span
          id="telPerfil"><?php echo $perfil_data['telefono'] ?? '0000000000'; ?></span></p>
      <p><strong>Fecha de nacimiento:</strong> <br><span
          id="fechaPerfil"><?php echo $perfil_data['fecha'] ?? '2000-01-01'; ?></span></p>
      <p><strong>Biografía:</strong> <br><span
          id="bioPerfil"><?php echo $perfil_data['bio'] ?? 'Biografía por defecto.'; ?></span></p>
      <button id="editar" onclick="activarEdicion()">Editar Perfil</button>
      <button id="editar" onclick="window.location.href='logout.php'">Cerrar sesión</button>
    </div>
    <form id="formularioPerfil" style="display: none;" method="POST" action="guardar_perfil.php"
      enctype="multipart/form-data">
      <label>Nombre:</label>
      <input type="text" name="nombre" value="<?php echo $perfil_data['nombre'] ?? 'Juan Pérez'; ?>">
      <label>Email:</label>
      <input type="email" name="email" value="<?php echo $perfil_data['email'] ?? 'juan@example.com'; ?>">
      <label>Teléfono:</label>
      <input type="text" name="telefono" value="<?php echo $perfil_data['telefono'] ?? '0000000000'; ?>">
      <label>Fecha de nacimiento:</label>
      <input type="date" name="fecha" value="<?php echo $perfil_data['fecha'] ?? '2000-01-01'; ?>">
      <label>Biografía:</label>
      <textarea name="bio" rows="3"><?php echo $perfil_data['bio'] ?? 'Biografía por defecto.'; ?></textarea>
      <label>Foto de perfil:</label>
      <input type="file" name="foto" accept="image/*">
      <button id="editar" type="submit">Guardar Cambios</button>
      <button id="editar" type="button" onclick="cancelarEdicion()" style="margin-top: 10px;">Cancelar</button>
    </form>
  </div>

  <script>
    // Dropdown interacciones
    const navItems = document.querySelectorAll('.nav-item');
    let currentDropdown = null;

    navItems.forEach(item => {
      const dropdown = item.querySelector('.dropdown');

      item.addEventListener('mouseenter', () => {
        if (window.innerWidth > 768) {
          if (currentDropdown && currentDropdown !== dropdown) {
            currentDropdown.classList.remove('active');
          }
          dropdown.classList.add('active');
          currentDropdown = dropdown;
        }
      });

      item.addEventListener('mouseleave', () => {
        if (window.innerWidth > 768) {
          setTimeout(() => {
            if (!dropdown.matches(':hover')) {
              dropdown.classList.remove('active');
              currentDropdown = null;
            }
          }, 100);
        }
      });

      dropdown.addEventListener('mouseleave', () => {
        if (window.innerWidth > 768) {
          dropdown.classList.remove('active');
          currentDropdown = null;
        }
      });
    });

    // Menú hamburguesa
    const hamburgerBtn = document.getElementById('hamburger-btn');
    const nav = document.querySelector('nav');

    hamburgerBtn.addEventListener('click', () => {
      nav.classList.toggle('active');
    });

    // Activar submenús con click en móviles
    const isMobile = () => window.innerWidth <= 768;

    document.querySelectorAll('.nav-item > a.sent').forEach(link => {
      link.addEventListener('click', (e) => {
        if (isMobile()) {
          e.preventDefault(); // Evita el salto de página
          const dropdown = link.nextElementSibling;

          if (dropdown && dropdown.classList.contains('dropdown')) {
            dropdown.classList.toggle('active');
          }
        }
      });
    });

    // Cerrar dropdowns móviles al hacer clic fuera
    document.addEventListener('click', (e) => {
      if (window.innerWidth <= 768) {
        const navItems = document.querySelectorAll('.nav-item');
        navItems.forEach(item => {
          const link = item.querySelector('a.sent');
          const dropdown = item.querySelector('.dropdown');
          if (!item.contains(e.target)) {
            dropdown?.classList.remove('active');
          }
        });
      }
    });

  </script>

  <script>
    const phrases = [
      "Innovación. Velocidad. Perfección.",
      "Diseñado para ganar.",
      "Más que un auto: una leyenda.",
      "Tecnología sin límites."
    ];

    let currentPhrase = 0;
    let currentChar = 0;
    const typedText = document.querySelector(".typed-text");

    function typePhrase() {
      if (currentChar < phrases[currentPhrase].length) {
        typedText.textContent += phrases[currentPhrase].charAt(currentChar);
        currentChar++;
        setTimeout(typePhrase, 70); // velocidad de tipeo
      } else {
        setTimeout(erasePhrase, 2000); // espera antes de borrar
      }
    }

    function erasePhrase() {
      if (currentChar > 0) {
        typedText.textContent = phrases[currentPhrase].substring(0, currentChar - 1);
        currentChar--;
        setTimeout(erasePhrase, 40); // velocidad de borrado
      } else {
        currentPhrase = (currentPhrase + 1) % phrases.length;
        setTimeout(typePhrase, 400);
      }
    }

    // Iniciar
    document.addEventListener("DOMContentLoaded", () => {
      typePhrase();
    });
  </script>



  <style>
    .typed-text.fade {
      transition: opacity 0.5s ease;
      opacity: 0.8;
    }
  </style>

  <script>
    function abrirPerfil() {
      document.getElementById("sidebar").classList.add("active");
      document.getElementById("vistaPerfil").style.display = "block";
      document.getElementById("formularioPerfil").style.display = "none";
    }
    function cerrarPerfil() {
      document.getElementById("sidebar").classList.remove("active");
    }
    function activarEdicion() {
      document.getElementById("vistaPerfil").style.display = "none";
      document.getElementById("formularioPerfil").style.display = "block";
    }
    function cancelarEdicion() {
      document.getElementById("formularioPerfil").style.display = "none";
      document.getElementById("vistaPerfil").style.display = "block";
    }
  </script>

  <script>
    let slideIndex = 1;
    const slides = document.querySelectorAll(".slide");
    const dots = document.querySelectorAll(".dot");
    const container = document.getElementById("carousel");

    // Obtener el ancho del slide incluyendo gap (20px)
    function getSlideWidth() {
      return slides[slideIndex].offsetWidth + 20;
    }

    function mostrarSlide(index, applyActive = true) {
      const slideWidth = getSlideWidth();
      container.style.transition = "transform 0.7s ease";
      container.style.transform = `translateX(calc(-${slideWidth * index}px + 10%))`;

      if (applyActive) {
        // Eliminar .active solo de slides reales
        slides.forEach(slide => {
          if (!slide.classList.contains("clone")) {
            slide.classList.remove("active");
          }
        });

        // Solo asignar .active si NO es un clon
        const currentSlide = slides[index];
        if (!currentSlide.classList.contains("clone")) {
          currentSlide.classList.add("active");
        }

        // Actualizar dots (solo en slides reales)
        dots.forEach(dot => dot.classList.remove("active"));
        let realIndex = index - 1;
        if (realIndex >= dots.length) realIndex = 0;
        if (realIndex < 0) realIndex = dots.length - 1;
        dots[realIndex].classList.add("active");
      }
    }


    function moverSlide(n) {
      slideIndex += n;
      mostrarSlide(slideIndex);
    }

    function irASlide(n) {
      slideIndex = n + 1; // Ajustar por slide duplicado
      mostrarSlide(slideIndex);
    }

    // Reinicio de transición al llegar a clones
    container.addEventListener("transitionend", () => {
      if (slideIndex === 0) {
        container.style.transition = "none";
        slideIndex = slides.length - 2;
        container.style.transform = `translateX(calc(-${getSlideWidth() * slideIndex}px + 10%))`;
        requestAnimationFrame(() => mostrarSlide(slideIndex, true));

      }
      if (slideIndex === slides.length - 1) {
        container.style.transition = "none";
        slideIndex = 1;
        container.style.transform = `translateX(calc(-${getSlideWidth() * slideIndex}px + 10%))`;
        requestAnimationFrame(() => mostrarSlide(slideIndex, true));

      }
    });

    setInterval(() => moverSlide(1), 6000);
    window.addEventListener("load", () => mostrarSlide(slideIndex));
  </script>


</body>

</html>