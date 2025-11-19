<?php
session_start();

// Verificar autenticación
if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true) {
    header('Location: admin-login.php');
    exit;
}

// Crear directorios si no existen
$dirs = ['blog-posts', 'uploads'];
foreach ($dirs as $dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0755, true);
    }
}

$success = '';
$error = '';

// Cargar datos existentes
$blog_file = 'blog-posts/blog-data.json';
$blog_data = file_exists($blog_file) ? json_decode(file_get_contents($blog_file), true) : [
    'hero' => ['titulo' => '', 'subtitulo' => '', 'imagen' => ''],
    'articulo1' => ['titulo' => '', 'contenido' => '', 'imagen' => ''],
    'articulo2' => ['titulo' => '', 'contenido' => '', 'imagen' => ''],
    'articulo3' => ['titulo' => '', 'contenido' => '', 'imagen' => '']
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form_type = $_POST['form_type'] ?? '';
    
    // Procesar imagen si existe
    $imagen = '';
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $filename = $_FILES['imagen']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $newname = 'blog_' . time() . '.' . $ext;
            $destination = 'uploads/' . $newname;
            
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $destination)) {
                $imagen = $destination;
            }
        }
    }
    
    switch ($form_type) {
        case 'hero':
            $blog_data['hero']['titulo'] = trim($_POST['titulo'] ?? '');
            $blog_data['hero']['subtitulo'] = trim($_POST['subtitulo'] ?? '');
            if ($imagen) $blog_data['hero']['imagen'] = $imagen;
            $success = '¡Hero actualizado exitosamente!';
            break;
            
        case 'articulo1':
            $blog_data['articulo1']['titulo'] = trim($_POST['titulo'] ?? '');
            $blog_data['articulo1']['contenido'] = trim($_POST['contenido'] ?? '');
            if ($imagen) $blog_data['articulo1']['imagen'] = $imagen;
            $success = '¡Artículo 1 actualizado exitosamente!';
            break;
            
        case 'articulo2':
            $blog_data['articulo2']['titulo'] = trim($_POST['titulo'] ?? '');
            $blog_data['articulo2']['contenido'] = trim($_POST['contenido'] ?? '');
            if ($imagen) $blog_data['articulo2']['imagen'] = $imagen;
            $success = '¡Artículo 2 actualizado exitosamente!';
            break;
            
        case 'articulo3':
            $blog_data['articulo3']['titulo'] = trim($_POST['titulo'] ?? '');
            $blog_data['articulo3']['contenido'] = trim($_POST['contenido'] ?? '');
            if ($imagen) $blog_data['articulo3']['imagen'] = $imagen;
            $success = '¡Artículo 3 actualizado exitosamente!';
            break;
    }
    
    if (file_put_contents($blog_file, json_encode($blog_data, JSON_PRETTY_PRINT))) {
        // Success message already set
    } else {
        $error = 'Error al guardar los cambios';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Blog - Dulces El Trapiche</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <style>
        body {
            background: #f8f9fa;
            padding: 20px;
        }
        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .header-admin {
            background: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .form-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .form-card h3 {
            color: #59981A;
            border-bottom: 3px solid #59981A;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .btn-submit {
            background: #b32f1e;
            border: none;
            padding: 12px 30px;
            font-weight: bold;
            color: white;
        }
        .btn-submit:hover {
            background: #8a2416;
            color: white;
        }
        .preview-img {
            max-width: 200px;
            margin-top: 10px;
            border-radius: 5px;
        }
        .badge-form {
            background: #59981A;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="header-admin">
            <div>
                <h2 class="mb-0">Panel de Administración del Blog</h2>
                <small class="text-muted">Gestiona el contenido de blog.html</small>
            </div>
            <a href="logout.php" class="btn btn-outline-danger">Cerrar Sesión</a>
        </div>
        
        <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php echo $success; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <!-- FORMULARIO 1: HERO VERDE -->
        <div class="form-card">
            <h3><span class="badge badge-form me-2">1</span> Hero Verde (Sección Superior)</h3>
            <p class="text-muted">Modifica el título y subtítulo de la sección verde con imagen</p>
            
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="form_type" value="hero">
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Título del Hero *</label>
                        <input type="text" name="titulo" class="form-control" 
                               value="<?php echo htmlspecialchars($blog_data['hero']['titulo'] ?? ''); ?>" 
                               placeholder="Ej: Los Beneficios de la Panela" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Subtítulo del Hero *</label>
                        <input type="text" name="subtitulo" class="form-control" 
                               value="<?php echo htmlspecialchars($blog_data['hero']['subtitulo'] ?? ''); ?>" 
                               placeholder="Ej: El endulzante natural que tu cuerpo necesita" required>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Imagen del Hero (opcional)</label>
                    <input type="file" name="imagen" class="form-control" accept="image/*" 
                           onchange="previewImage(this, 'preview1')">
                    <?php if (!empty($blog_data['hero']['imagen'])): ?>
                        <img src="<?php echo $blog_data['hero']['imagen']; ?>" class="preview-img" id="current1">
                    <?php endif; ?>
                    <img id="preview1" class="preview-img" style="display:none;">
                </div>
                
                <button type="submit" class="btn btn-submit">Actualizar Hero</button>
            </form>
        </div>
        
        <!-- FORMULARIO 2: ARTÍCULO 1 -->
        <div class="form-card">
            <h3><span class="badge badge-form me-2">2</span> Artículo Principal</h3>
            <p class="text-muted">Contenido del primer artículo del blog</p>
            
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="form_type" value="articulo1">
                
                <div class="mb-3">
                    <label class="form-label">Título del Artículo *</label>
                    <input type="text" name="titulo" class="form-control" 
                           value="<?php echo htmlspecialchars($blog_data['articulo1']['titulo'] ?? ''); ?>" 
                           placeholder="Ej: ¿Por qué la Panela es Superior al Azúcar?" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Imagen del Artículo (opcional)</label>
                    <input type="file" name="imagen" class="form-control" accept="image/*" 
                           onchange="previewImage(this, 'preview2')">
                    <?php if (!empty($blog_data['articulo1']['imagen'])): ?>
                        <img src="<?php echo $blog_data['articulo1']['imagen']; ?>" class="preview-img" id="current2">
                    <?php endif; ?>
                    <img id="preview2" class="preview-img" style="display:none;">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Contenido del Artículo *</label>
                    <textarea name="contenido" class="form-control" rows="12" required 
                              placeholder="Escribe el contenido completo del artículo con HTML..."><?php echo htmlspecialchars($blog_data['articulo1']['contenido'] ?? ''); ?></textarea>
                    <small class="text-muted">Puedes usar HTML: &lt;h3&gt;, &lt;p&gt;, &lt;ul&gt;, &lt;strong&gt;, etc.</small>
                </div>
                
                <button type="submit" class="btn btn-submit">Actualizar Artículo 1</button>
            </form>
        </div>
        
        <!-- FORMULARIO 3: ARTÍCULO 2 -->
        <div class="form-card">
            <h3><span class="badge badge-form me-2">3</span> Artículo Secundario 1</h3>
            <p class="text-muted">Segundo artículo del blog (fondo gris claro)</p>
            
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="form_type" value="articulo2">
                
                <div class="mb-3">
                    <label class="form-label">Título del Artículo</label>
                    <input type="text" name="titulo" class="form-control" 
                           value="<?php echo htmlspecialchars($blog_data['articulo2']['titulo'] ?? ''); ?>" 
                           placeholder="Ej: Historia de Dulces El Trapiche">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Imagen del Artículo (opcional)</label>
                    <input type="file" name="imagen" class="form-control" accept="image/*" 
                           onchange="previewImage(this, 'preview3')">
                    <?php if (!empty($blog_data['articulo2']['imagen'])): ?>
                        <img src="<?php echo $blog_data['articulo2']['imagen']; ?>" class="preview-img" id="current3">
                    <?php endif; ?>
                    <img id="preview3" class="preview-img" style="display:none;">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Contenido del Artículo</label>
                    <textarea name="contenido" class="form-control" rows="12" 
                              placeholder="Escribe el contenido del segundo artículo..."><?php echo htmlspecialchars($blog_data['articulo2']['contenido'] ?? ''); ?></textarea>
                    <small class="text-muted">Puedes usar HTML: &lt;h3&gt;, &lt;p&gt;, &lt;ul&gt;, &lt;strong&gt;, etc.</small>
                </div>
                
                <button type="submit" class="btn btn-submit">Actualizar Artículo 2</button>
            </form>
        </div>
        
        <!-- FORMULARIO 4: ARTÍCULO 3 -->
        <div class="form-card">
            <h3><span class="badge badge-form me-2">4</span> Artículo Secundario 2</h3>
            <p class="text-muted">Tercer artículo del blog (fondo blanco)</p>
            
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="form_type" value="articulo3">
                
                <div class="mb-3">
                    <label class="form-label">Título del Artículo</label>
                    <input type="text" name="titulo" class="form-control" 
                           value="<?php echo htmlspecialchars($blog_data['articulo3']['titulo'] ?? ''); ?>" 
                           placeholder="Ej: Recetas con Panela Natural">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Imagen del Artículo (opcional)</label>
                    <input type="file" name="imagen" class="form-control" accept="image/*" 
                           onchange="previewImage(this, 'preview4')">
                    <?php if (!empty($blog_data['articulo3']['imagen'])): ?>
                        <img src="<?php echo $blog_data['articulo3']['imagen']; ?>" class="preview-img" id="current4">
                    <?php endif; ?>
                    <img id="preview4" class="preview-img" style="display:none;">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Contenido del Artículo</label>
                    <textarea name="contenido" class="form-control" rows="12" 
                              placeholder="Escribe el contenido del tercer artículo..."><?php echo htmlspecialchars($blog_data['articulo3']['contenido'] ?? ''); ?></textarea>
                    <small class="text-muted">Puedes usar HTML: &lt;h3&gt;, &lt;p&gt;, &lt;ul&gt;, &lt;strong&gt;, etc.</small>
                </div>
                
                <button type="submit" class="btn btn-submit">Actualizar Artículo 3</button>
            </form>
        </div>
        
        <div class="text-center mb-5">
            <a href="blog.html" target="_blank" class="btn btn-success btn-lg">Ver Blog Público</a>
            <a href="index.html" class="btn btn-outline-secondary btn-lg">Volver al Sitio</a>
        </div>
    </div>
    
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script>
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>
