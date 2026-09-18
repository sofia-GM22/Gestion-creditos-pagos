# Sistema de Gestión de Créditos y Pagos

Aplicación web desarrollada con Laravel para administrar clientes, créditos y pagos, aplicando relaciones Eloquent, validaciones, reglas de negocio, control de acceso y transacciones de base de datos.

## Tecnologías

- PHP 8.2 o superior
- Laravel 12
- MySQL
- XAMPP
- Blade
- Bootstrap 5
- PHPUnit
- Composer
- Git

## Requisitos previos

Antes de ejecutar el proyecto, se recomienda tener instalado:

1. PHP 8.2 o superior.
2. Composer.
3. MySQL mediante XAMPP u otra instalación compatible.
4. Git.
5. Un navegador web.

Para comprobar PHP y Composer:

```bash
php -v
composer -V
```

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

```text
total_credito = monto + (monto * tasa_interes / 100)
```

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

El sistema valida que un cliente no pueda consultar información perteneciente a otro cliente.

## Base de datos

Las principales tablas del sistema son:

- `users`
- `roles`
- `clientes`
- `creditos`
- `pagos`

Además, Laravel utiliza tablas auxiliares para sesiones, caché y trabajos en cola.

### Relaciones principales

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
```

### 2. Instalar dependencias PHP

```bash
composer install
```

### 3. Crear el archivo `.env`

Si utilizas Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Si utilizas CMD:

```cmd
copy .env.example .env
```

### 4. Crear la base de datos

Abrir XAMPP y activar:

- MySQL

Apache no es obligatorio si se utilizará `php artisan serve`.

Crear una base de datos llamada:

```text
sistema_gestion_creditos
```

Se puede crear desde phpMyAdmin.

### 5. Configurar la base de datos

Abrir el archivo `.env` y verificar:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sistema_gestion_creditos
DB_USERNAME=root
DB_PASSWORD=
```

Si la instalación local de MySQL utiliza una contraseña para `root`, colocarla en:

```env
DB_PASSWORD=TU_CONTRASEÑA
```

No subir nunca el archivo `.env` a GitHub.

### 6. Generar la clave de Laravel

```bash
php artisan key:generate
```

### 7. Ejecutar migraciones y datos iniciales

```bash
php artisan migrate --seed
```

Este comando crea las tablas necesarias y registra los usuarios de prueba.

### 8. Limpiar la configuración de Laravel

```bash
php artisan optimize:clear
```

### 9. Ejecutar las pruebas

```bash
php artisan test
```

El proyecto debe mostrar:

```text
Tests: 8 passed (28 assertions)
```

### 10. Iniciar el servidor

```bash
php artisan serve
```

Luego abrir en el navegador:

```text
http://127.0.0.1:8000
```

También puede utilizarse:

```text
http://localhost:8000
```

## Usuarios de prueba

El proyecto incluye usuarios de prueba mediante `DatabaseSeeder`.

### Administrador

```text
Usuario: Maestro
Contraseña: ITCA2026
Rol: Administrador
```

### Empleado

```text
Usuario: Daniel
Contraseña: DSW22
Rol: Empleado
```

### Cliente

```text
Usuario: Sofia
Contraseña: Gomez22
Rol: Cliente
```

El usuario `Sofia` tiene un cliente asociado creado automáticamente por el seeder.

## Flujo recomendado para probar el sistema

### Administrador o empleado

1. Iniciar sesión con `Maestro` o `Daniel`.
2. Crear o consultar clientes.
3. Registrar un crédito para un cliente.
4. Verificar el cálculo automático del total.
5. Verificar el saldo inicial.
6. Registrar un pago parcial.
7. Verificar la actualización del saldo.
8. Registrar el pago restante.
9. Verificar que el crédito cambie a `Pagado`.
10. Consultar el historial de pagos.
11. Generar o imprimir el comprobante.

### Cliente

1. Iniciar sesión como `Sofia`.
2. Entrar a `Mis Créditos`.
3. Consultar sus créditos.
4. Entrar a `Mis Pagos`.
5. Consultar su historial.
6. Verificar que no pueda acceder a información de otro cliente.

## Pruebas automatizadas

El proyecto incluye pruebas funcionales para:

- Registro de créditos.
- Cálculo del total y saldo.
- Pagos parciales.
- Pagos completos.
- Cambio automático del estado a `Pagado`.
- Rechazo de pagos mayores al saldo.
- Cambio automático a `Vencido`.
- Restricción de acceso de un cliente al crédito de otro cliente.

Para ejecutar todas las pruebas:

```bash
php artisan test
```

Resultado esperado:

```text
Tests: 8 passed (28 assertions)
```

## Verificar migraciones

Para consultar el estado de las migraciones:

```bash
php artisan migrate:status
```

## Reiniciar completamente la base de datos

Durante desarrollo o pruebas se puede utilizar:

```bash
php artisan migrate:fresh --seed
```

### Advertencia

`migrate:fresh` elimina todas las tablas y todos los datos existentes de la base de datos configurada.

No ejecutar este comando en una base de datos que contenga información que se necesite conservar.

## Estructura general del proyecto

```text
app/
├── Http/
│   ├── Controllers/
│   ├── Middleware/
│   └── Requests/
├── Models/

database/
├── migrations/
└── seeders/

resources/
└── views/

routes/
└── web.php

tests/
├── Feature/
└── Unit/
```

## Principales componentes

### Modelos

- `User`
- `Role`
- `Cliente`
- `Credito`
- `Pago`

### Controladores

- `AuthController`
- `ClienteController`
- `CreditoController`
- `PagoController`

### Solicitudes de validación

- `ClienteRequest`
- `CreditoRequest`
- `PagoRequest`

### Middleware

- `CheckRole`

## Buenas prácticas utilizadas

El proyecto implementa:

- Arquitectura MVC de Laravel.
- Relaciones Eloquent.
- Migraciones.
- Seeders.
- Validaciones.
- Middleware de autorización.
- Transacciones de base de datos.
- Bloqueo de filas para actualización de saldo.
- Paginación.
- Búsqueda y filtros.
- Pruebas automatizadas.
- Separación entre configuración local y código versionado.

## Problemas comunes

### Error de conexión a MySQL

Verificar que:

1. MySQL esté iniciado en XAMPP.
2. La base de datos `sistema_gestion_creditos` exista.
3. Los datos de conexión del `.env` sean correctos.

Después ejecutar:

```bash
php artisan optimize:clear
```

### Error relacionado con la clave de aplicación

Ejecutar:

```bash
php artisan key:generate
```

### Error por cambios en configuración

Ejecutar:

```bash
php artisan optimize:clear
```

### Error de dependencias

Ejecutar nuevamente:

```bash
composer install
```

## Propuesta de pruebas para compañeros

Después de instalar el proyecto, se recomienda validar como mínimo:

### Clientes

- Crear un cliente.
- Editar un cliente.
- Consultar un cliente.
- Desactivar un cliente.

### Créditos

- Crear un crédito.
- Verificar cálculo del total.
- Verificar saldo inicial.
- Verificar fecha de vencimiento.
- Consultar créditos por estado.

### Pagos

- Registrar un pago parcial.
- Registrar el pago restante.
- Verificar cambio a `Pagado`.
- Intentar registrar un pago mayor al saldo.
- Consultar historial de pagos.
- Revisar comprobante.

### Seguridad

- Iniciar sesión con distintos roles.
- Verificar acceso según el rol.
- Iniciar sesión como cliente e intentar consultar información de otro cliente.

## Reporte de observaciones

Si encuentras un error, comportamiento inesperado o una mejora durante las pruebas, se recomienda indicar:

```text
Usuario utilizado:
Módulo:
Pasos realizados:
Resultado esperado:
Resultado obtenido:
Mensaje de error (si existe):
Captura de pantalla (si aplica):
```

También son bienvenidas observaciones sobre:

- Diseño de la interfaz.
- Facilidad de uso.
- Validaciones.
- Mensajes mostrados al usuario.
- Flujo de clientes, créditos y pagos.
- Seguridad y permisos.
- Errores encontrados.
- Mejoras propuestas.

## Estado actual del proyecto

El proyecto cuenta con:

- Autenticación.
- Roles y permisos.
- Gestión de clientes.
- Gestión de créditos.
- Gestión de pagos.
- Cálculo automático de saldo.
- Cambio automático de estados.
- Historial de pagos.
- Comprobantes imprimibles.
- Validaciones.
- Transacciones.
- Pruebas automatizadas.

## Repositorio

Repositorio principal:

https://github.com/sofia-GM22/Gestion-creditos-pagos
