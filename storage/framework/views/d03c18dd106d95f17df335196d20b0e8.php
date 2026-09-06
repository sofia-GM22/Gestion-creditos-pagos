<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('titulo', 'Sistema de Gestión de Créditos'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
 
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="<?php echo e(route('home')); ?>">Sistema de Gestión de Créditos</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('clientes.index')); ?>">Clientes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('clientes.por-estado-credito')); ?>">Créditos por estado</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" href="#" tabindex="-1">Créditos (próximamente)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" href="#" tabindex="-1">Pagos (próximamente)</a>
                    </li>

                                    </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <span class="navbar-text text-light me-3">
                            <?php echo e(auth()->user()->username); ?> (<?php echo e(auth()->user()->role->nombre); ?>)
                        </span>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-outline-light btn-sm">Cerrar sesión</button>
                        </form>
                    </li>
                </ul>
                
                </ul>
            </div>
        </div>
    </nav>
 
    <div class="container pb-5">
        <?php if(session('exito')): ?>
            <div class="alert alert-success"><?php echo e(session('exito')); ?></div>
        <?php endif; ?>
 
        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>
 
        <?php echo $__env->yieldContent('content'); ?>
    </div>
 
</body>
</html>
 
<?php /**PATH C:\xampp\htdocs\sistema-gestion-creditos\resources\views/layouts/app.blade.php ENDPATH**/ ?>