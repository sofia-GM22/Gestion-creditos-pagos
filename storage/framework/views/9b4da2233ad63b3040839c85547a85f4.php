<!DOCTYPE html>
<html>
<head><title>Nuevo Cliente</title></head>
<body>
    <h1>Nuevo Cliente</h1>

    <?php if($errors->any()): ?>
        <ul style="color:red">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('clientes.store')); ?>">
        <?php echo csrf_field(); ?>
        <label>Nombres</label><br>
        <input type="text" name="nombres" value="<?php echo e(old('nombres')); ?>"><br>

        <label>Apellidos</label><br>
        <input type="text" name="apellidos" value="<?php echo e(old('apellidos')); ?>"><br>

        <label>Documento de identidad</label><br>
        <input type="text" name="documento_identidad" value="<?php echo e(old('documento_identidad')); ?>"><br>

        <label>Teléfono</label><br>
        <input type="text" name="telefono" value="<?php echo e(old('telefono')); ?>"><br>

        <label>Correo</label><br>
        <input type="email" name="correo" value="<?php echo e(old('correo')); ?>"><br>

        <label>Dirección</label><br>
        <input type="text" name="direccion" value="<?php echo e(old('direccion')); ?>"><br><br>

        <button type="submit">Guardar</button>
        <a href="<?php echo e(route('clientes.index')); ?>">Cancelar</a>
    </form>
</body>
</html><?php /**PATH C:\xampp\htdocs\sistema-gestion-creditos\resources\views/clientes/create.blade.php ENDPATH**/ ?>