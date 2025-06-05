<?php
// ajustes.php
session_start();
// Simulación de datos de usuario
$usuario = [
  'nombre' => 'Juan Pérez',
  'email' => 'juanperez@example.com',
  'notificaciones' => true
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Procesar datos del formulario
  $usuario['nombre'] = $_POST['nombre'] ?? $usuario['nombre'];
  $usuario['email'] = $_POST['email'] ?? $usuario['email'];
  $usuario['notificaciones'] = isset($_POST['notificaciones']);
  $mensaje = "Cambios guardados exitosamente.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Ajustes</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(to right, #0f2027, #203a43, #2c5364);
      color: #fff;
      padding: 40px;
    }
    h1 {
      text-align: center;
      margin-bottom: 40px;
    }
    form {
      max-width: 600px;
      margin: 0 auto;
      background: rgba(255,255,255,0.1);
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.5);
    }
    label {
      display: block;
      margin-top: 20px;
      font-weight: bold;
    }
    input[type="text"],
    input[type="email"] {
      width: 100%;
      padding: 10px;
      border: none;
      border-radius: 5px;
      margin-top: 5px;
    }
    input[type="checkbox"] {
      margin-top: 10px;
    }
    button {
      margin-top: 30px;
      padding: 10px 20px;
      background-color: #00d4ff;
      border: none;
      border-radius: 5px;
      color: #000;
      font-weight: bold;
      cursor: pointer;
    }
    .mensaje {
      margin-top: 20px;
      text-align: center;
      color: #00ffb3;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <h1>Ajustes de Usuario</h1>

  <?php if (isset($mensaje)): ?>
    <div class="mensaje"><?= $mensaje ?></div>
  <?php endif; ?>

  <form method="POST" action="">
    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>">

    <label for="email">Correo electrónico:</label>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>">

    <label>
      <input type="checkbox" name="notificaciones" <?= $usuario['notificaciones'] ? 'checked' : '' ?>>
      Recibir notificaciones
    </label>

    <button type="submit">Guardar Cambios</button>
  </form>
</body>
</html>
