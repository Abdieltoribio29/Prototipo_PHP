<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $bio = $_POST['bio'];
    $telefono = $_POST['telefono'];
    $fecha = $_POST['fecha'];
    $foto_path = '';

    // Manejo de la imagen subida
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $nombre_foto = uniqid() . '_' . basename($_FILES['foto']['name']);
        $ruta_destino = 'uploads/' . $nombre_foto;
        $tipo_archivo = strtolower(pathinfo($ruta_destino, PATHINFO_EXTENSION));

        // Validar tipo
        $tipos_permitidos = ['jpg', 'jpeg', 'png'];
        if (in_array($tipo_archivo, $tipos_permitidos)) {
            move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_destino);
            $foto_path = $ruta_destino;
        }
    }

    // Guardar los datos como JSON
    $perfil = [
        'nombre' => $nombre,
        'email' => $email,
        'bio' => $bio,
        'telefono' => $telefono,
        'fecha' => $fecha,
        'foto' => $foto_path
    ];

    file_put_contents('perfil.json', json_encode($perfil, JSON_PRETTY_PRINT));

    // Redirigir de vuelta al dashboard
    header('Location: Principal.php');
    exit();
} else {
    echo "Acceso denegado.";
}
?>
