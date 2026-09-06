

<?php $__env->startSection('titulo', 'Editar Cliente'); ?>

<?php $__env->startSection('content'); ?>
    <h1 class="h3 mb-4">Editar Cliente</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="<?php echo e(route('clientes.update', $cliente)); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombres</label>
                        <input type="text" name="nombres" value="<?php echo e(old('nombres', $cliente->nombres)); ?>" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Apellidos</label>
                        <input type="text" name="apellidos" value="<?php echo e(old('apellidos', $cliente->apellidos)); ?>" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Documento de identidad</label>
                        <input type="text" name="documento_identidad" value="<?php echo e(old('documento_identidad', $cliente->documento_identidad)); ?>" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" value="<?php echo e(old('telefono', $cliente->telefono)); ?>" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Correo</label>
                        <input type="email" name="correo" value="<?php echo e(old('correo', $cliente->correo)); ?>" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Dirección</label>
                        <input type="text" name="direccion" value="<?php echo e(old('direccion', $cliente->direccion)); ?>" class="form-control">
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <a href="<?php echo e(route('clientes.index')); ?>" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sistema-gestion-creditos\resources\views/clientes/edit.blade.php ENDPATH**/ ?>