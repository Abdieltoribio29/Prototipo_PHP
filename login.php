<?php
// login.php
session_start();

// Credenciales de ejemplo
$usuario_correcto = 'admin';
$contrasena_correcta = '222';

// Redirección si ya ha iniciado sesión
if (isset($_SESSION['logueado']) && $_SESSION['logueado'] === true) {
  header('Location: Principal.php');
  exit();
}

$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $usuario = $_POST['usuario'] ?? '';
  $contrasena = $_POST['contrasena'] ?? '';

  if ($usuario === $usuario_correcto && $contrasena === $contrasena_correcta) {
    $_SESSION['logueado'] = true;
    $_SESSION['usuario'] = $usuario;
    header('Location: Principal.php');
    exit();
  } else {
    $mensaje = 'Usuario o contraseña incorrectos';
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Iniciar Sesión</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-image: url("espacio.jpg");
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      color: white;
    }
    .login-container {
      background: rgba(255, 255, 255, 0.1);
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.5);
      backdrop-filter: blur(10px);
      text-align: center;
      width: 350px;
      animation: fadeIn 1s ease;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .login-container h2 {
      margin-bottom: 20px;
      color: #00d4ff;
    }
    .login-container input {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: none;
      border-radius: 8px;
      outline: none;
      background-color: rgba(255,255,255,0.2);
      color: white;
      font-size: 1rem;
    }
    .login-container button {
      width: 100%;
      padding: 12px;
      background-color: #00d4ff;
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      font-weight: bold;
      cursor: pointer;
      color: #000;
      transition: background-color 0.3s ease;
    }
    .login-container button:hover {
      background-color: #00aacc;
    }
    .mensaje {
      margin-top: 15px;
      color: #ff7373;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h2>Iniciar Sesión</h2>
    <?php if ($mensaje): ?>
      <div class="mensaje"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>
    <form method="POST">
      <input type="text" name="usuario" placeholder="Usuario" required>
      <input type="password" name="contrasena" placeholder="Contraseña" required>
      <button type="submit">Entrar</button>
    </form>
  </div>
</body>
</html>
