# Sistema de Gestión de Créditos y Pagos

Aplicación web desarrollada con Laravel para administrar clientes, créditos y pagos, aplicando relaciones Eloquent, validaciones, reglas de negocio, control de acceso y transacciones de base de datos.

## Tecnologías

- PHP 8.2+
- Laravel 12
- MySQL / XAMPP
- Blade
- Bootstrap 5
- PHPUnit

## Funcionalidades principales

### Autenticación y roles

El sistema maneja tres roles:

- Administrador
- Empleado
- Cliente

Los administradores y empleados pueden gestionar clientes, créditos y pagos.

Los clientes solamente pueden consultar sus propios créditos y pagos.

## Módulo de clientes

Permite:

- Registrar clientes.
- Editar información.
- Consultar clientes.
- Desactivar clientes.
- Consultar clientes según el estado de sus créditos.

## Módulo de créditos

Permite:

- Registrar un crédito para un cliente existente.
- Calcular automáticamente el total del crédito.
- Inicializar el saldo pendiente.
- Calcular automáticamente la fecha de vencimiento según el plazo.
- Filtrar créditos por estado.
- Buscar créditos por datos del cliente.
- Consultar el detalle de cada crédito.

### Cálculo del crédito

El total se calcula como:

`total_credito = monto + (monto * tasa_interes / 100)`

Al registrar el crédito:

- El saldo inicial es igual al total del crédito.
- El estado inicial es `Activo`.
- La fecha de vencimiento se calcula sumando el plazo en meses a la fecha de otorgamiento.

## Módulo de pagos

Permite:

- Registrar pagos parciales.
- Registrar pagos completos.
- Consultar historial general de pagos.
- Consultar los pagos propios del cliente.
- Generar comprobantes imprimibles.

### Reglas de pagos

- El monto del pago debe ser mayor a cero.
- No se permite pagar más que el saldo pendiente.
- El saldo se actualiza automáticamente.
- Cuando el saldo llega a `0`, el crédito pasa automáticamente a `Pagado`.
- Los pagos se registran mediante una transacción de base de datos.
- Se utiliza bloqueo de fila (`lockForUpdate`) para proteger el saldo ante operaciones concurrentes.

## Estados de los créditos

Los créditos pueden manejar los siguientes estados:

- `Activo`
- `Pagado`
- `Vencido`

Un crédito activo con saldo pendiente cuya fecha de vencimiento ya pasó se actualiza automáticamente a `Vencido`.

## Seguridad y permisos

Las rutas administrativas están protegidas mediante autenticación y middleware de roles.

Un usuario con rol Cliente solamente puede consultar:

- Sus propios créditos.
- Sus propios pagos.
- Sus propios comprobantes.

El sistema valida explícitamente que un cliente no pueda consultar información perteneciente a otro cliente.

## Base de datos

Las principales tablas del sistema son:

- `users`
- `roles`
- `clientes`
- `creditos`
- `pagos`

Relaciones principales:

- Un usuario puede estar vinculado a un cliente.
- Un cliente puede tener muchos créditos.
- Un crédito pertenece a un cliente.
- Un crédito puede tener muchos pagos.
- Un pago pertenece a un crédito.

## Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/sofia-GM22/Gestion-creditos-pagos.git
cd Gestion-creditos-pagos