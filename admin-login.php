<?php
session_start();

// Configuración de credenciales (CAMBIA ESTOS VALORES)
define('ADMIN_USER', 'admin');
define('ADMIN_PASS', password_hash('trapiche2025', PASSWORD_DEFAULT)); // Cambia 'trapiche2025' por tu contraseña

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($username === ADMIN_USER && password_verify($password, ADMIN_PASS)) {
        $_SESSION['admin_logged'] = true;
        $_SESSION['admin_user'] = $username;
        header('Location: admin-blog.php');
        exit;
    } else {
        $error = 'Usuario o contraseña incorrectos';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración - Dulces El Trapiche</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #59981A 0%, #3d6b12 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            max-width: 400px;
            width: 100%;
        }
        .logo {
            max-width: 200px;
            margin-bottom: 30px;
        }
        .btn-login {
            background: #b32f1e;
            border: none;
            padding: 12px;
            font-weight: bold;
        }
        .btn-login:hover {
            background: #8a2416;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="text-center">
            <img src="assets/img/logo_final.png" alt="El Trapiche" class="logo">
            <h2 class="mb-4">Administración de Blog</h2>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Usuario</label>
                <input type="text" name="username" class="form-control" required autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-login btn-danger w-100">Ingresar</button>
        </form>
        
        <div class="text-center mt-3">
            <small class="text-muted">Panel de administración seguro</small>
        </div>
    </div>
</body>
</html>
