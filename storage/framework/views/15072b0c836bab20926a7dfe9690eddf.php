

<?php $__env->startSection('titulo', 'Clientes'); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Clientes</h1>
        <a href="<?php echo e(route('clientes.create')); ?>" class="btn btn-success">+ Nuevo cliente</a>
    </div>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-auto">
            <input type="text" name="buscar" value="<?php echo e(request('buscar')); ?>"
                   class="form-control" placeholder="Buscar por nombre, apellido o documento">
        </div>
        <div class="col-auto">
            <select name="estado" class="form-select">
                <option value="">Todos los estados</option>
                <option value="Activo" <?php if(request('estado')=='Activo'): echo 'selected'; endif; ?>>Activo</option>
                <option value="Inactivo" <?php if(request('estado')=='Inactivo'): echo 'selected'; endif; ?>>Inactivo</option>
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-outline-primary">Buscar</button>
        </div>
    </form>

    <div class="card shadow-sm">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Nombre</th>
                    <th>Documento</th>
                    <th>Teléfono</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($cliente->nombres); ?> <?php echo e($cliente->apellidos); ?></td>
                    <td><?php echo e($cliente->documento_identidad); ?></td>
                    <td><?php echo e($cliente->telefono ?? '—'); ?></td>
                    <td>
                        <span class="badge <?php echo e($cliente->estado === 'Activo' ? 'bg-success' : 'bg-secondary'); ?>">
                            <?php echo e($cliente->estado); ?>

                        </span>
                    </td>
                    <td class="text-end">
                        <a href="<?php echo e(route('clientes.show', $cliente)); ?>" class="btn btn-sm btn-outline-secondary">Ver</a>
                        <a href="<?php echo e(route('clientes.edit', $cliente)); ?>" class="btn btn-sm btn-outline-primary">Editar</a>
                        <form action="<?php echo e(route('clientes.desactivar', $cliente)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                            <button type="submit" class="btn btn-sm btn-outline-<?php echo e($cliente->estado === 'Activo' ? 'danger' : 'success'); ?>">
                                <?php echo e($cliente->estado === 'Activo' ? 'Desactivar' : 'Activar'); ?>

                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">No hay clientes registrados todavía.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        <?php echo e($clientes->links()); ?>

    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sistema-gestion-creditos\resources\views/clientes/index.blade.php ENDPATH**/ ?>