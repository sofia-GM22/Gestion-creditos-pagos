# Sistema de Gestión de Créditos y Pagos

Aplicación web desarrollada con Laravel 12 para administrar clientes, créditos, pagos y usuarios del sistema.

El proyecto implementa autenticación, roles, validaciones, reglas de negocio, relaciones Eloquent, transacciones de base de datos, control de acceso y una interfaz administrativa con diseño responsive.

---

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

---

## Requisitos previos

Antes de ejecutar el proyecto se recomienda tener instalado:

1. PHP 8.2 o superior.
2. Composer.
3. MySQL mediante XAMPP u otra instalación compatible.
4. Git.
5. Un navegador web.

Para comprobar PHP y Composer:

```bash
php -v
composer -V
````

---

## Funcionalidades principales

### Autenticación y roles

El sistema maneja tres roles:

* Administrador
* Empleado
* Cliente

#### Administrador

Puede:

* Gestionar clientes.
* Gestionar créditos.
* Gestionar pagos.
* Gestionar empleados.
* Activar y desactivar empleados.

#### Empleado

Puede:

* Gestionar clientes.
* Gestionar créditos.
* Gestionar pagos.

No puede acceder al módulo de empleados.

#### Cliente

Puede:

* Consultar sus propios créditos.
* Consultar sus propios pagos.
* Registrar pagos sobre sus propios créditos.
* Consultar sus propios comprobantes.

Un cliente no puede acceder a créditos, pagos o comprobantes pertenecientes a otro cliente.

---

## Módulo de clientes

Permite:

* Registrar clientes.
* Editar información.
* Consultar clientes.
* Buscar clientes.
* Filtrar clientes por estado.
* Desactivar y activar clientes.
* Consultar clientes según el estado de sus créditos.

### Validaciones de clientes

El sistema valida:

* DUI con 9 dígitos.
* Formato automático del DUI `123456789` → `12345678-9`.
* Teléfono de exactamente 8 dígitos.
* Documento de identidad único.
* Mensajes de validación en español.

---

## Módulo de empleados

Disponible únicamente para Administradores.

Permite:

* Registrar empleados.
* Editar empleados.
* Cambiar contraseña.
* Activar empleados.
* Desactivar empleados.
* Buscar empleados.
* Filtrar empleados por estado.

Los empleados creados desde este módulo reciben automáticamente el rol `Empleado`.

---

## Módulo de créditos

Permite:

* Registrar un crédito para un cliente existente.
* Calcular automáticamente el total del crédito.
* Inicializar el saldo pendiente.
* Calcular automáticamente la fecha de vencimiento.
* Buscar créditos por información del cliente.
* Filtrar créditos por estado.
* Consultar el detalle de cada crédito.
* Consultar créditos activos, pagados y vencidos.

### Monto mínimo

El sistema establece un monto mínimo de crédito de:

```text
$200.00
```

Los valores inferiores son rechazados mediante validación.

### Cálculo del crédito

El total se calcula mediante:

```text
total_credito = monto + (monto * tasa_interes / 100)
```

Al registrar un crédito:

* El saldo inicial es igual al total del crédito.
* El estado inicial es `Activo`.
* La fecha de vencimiento se calcula según el plazo en meses.

---

## Módulo de pagos

Permite:

* Registrar pagos parciales.
* Registrar pagos completos.
* Consultar historial general de pagos.
* Consultar los pagos propios del cliente.
* Registrar pagos propios desde una cuenta Cliente.
* Consultar comprobantes.
* Imprimir comprobantes.

### Reglas de pagos

* El monto debe ser mayor a cero.
* No se permite pagar más que el saldo pendiente.
* El saldo se actualiza automáticamente.
* Cuando el saldo llega a `0`, el crédito cambia a `Pagado`.
* Los pagos se registran mediante una transacción de base de datos.
* Se utiliza `lockForUpdate` para proteger el saldo ante operaciones concurrentes.

---

## Estados de los créditos

Los créditos pueden tener los siguientes estados:

* `Activo`
* `Pagado`
* `Vencido`

Un crédito activo cuya fecha de vencimiento ya pasó se actualiza automáticamente a `Vencido`.

---

## Seguridad y permisos

Las rutas protegidas utilizan autenticación y middleware de roles.

El sistema también implementa:

* Protección de rutas administrativas.
* Restricción de acceso por rol.
* Validación de propiedad de créditos para clientes.
* Protección contra acceso de un cliente a información de otro cliente.
* Protección de páginas autenticadas contra caché del navegador después del cierre de sesión.

---

## Base de datos

Las principales tablas son:

* `users`
* `roles`
* `clientes`
* `creditos`
* `pagos`

Laravel también utiliza tablas auxiliares para sesiones, caché y trabajos en cola.

### Relaciones principales

* Un usuario puede estar vinculado a un cliente.
* Un cliente puede tener muchos créditos.
* Un crédito pertenece a un cliente.
* Un crédito puede tener muchos pagos.
* Un pago pertenece a un crédito.

---

## Diseño de la interfaz

La aplicación cuenta con una interfaz administrativa responsive que incluye:

* Tema oscuro.
* Barra de navegación renovada.
* Tarjetas y formularios consistentes.
* Tablas responsive.
* Indicadores visuales para estados.
* Formularios con validación visual.
* Pantalla de inicio de sesión rediseñada.
* Comprobantes de pago preparados para impresión.
* Navegación diferenciada según el rol del usuario.

---

## Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/sofia-GM22/Gestion-creditos-pagos.git
cd Gestion-creditos-pagos
```

### 2. Instalar dependencias

```bash
composer install
```

### 3. Crear el archivo `.env`

En PowerShell:

```powershell
Copy-Item .env.example .env
```

En CMD:

```cmd
copy .env.example .env
```

### 4. Crear la base de datos

Abrir XAMPP y activar MySQL.

Crear una base de datos llamada:

```text
sistema_gestion_creditos
```

Puede crearse desde phpMyAdmin.

Apache no es obligatorio si se utilizará:

```bash
php artisan serve
```

### 5. Configurar la base de datos

En `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sistema_gestion_creditos
DB_USERNAME=root
DB_PASSWORD=
```

Si MySQL utiliza contraseña para `root`, configurar:

```env
DB_PASSWORD=TU_CONTRASEÑA
```

No subir nunca `.env` a GitHub.

### 6. Generar la clave de Laravel

```bash
php artisan key:generate
```

### 7. Ejecutar migraciones y seeders

```bash
php artisan migrate --seed
```

Esto crea las tablas necesarias y registra los usuarios de prueba.

### 8. Limpiar la configuración

```bash
php artisan optimize:clear
```

### 9. Ejecutar las pruebas

```bash
php artisan test
```

Resultado actual esperado:

```text
Tests:    29 passed (104 assertions)
```

### 10. Iniciar el servidor

```bash
php artisan serve
```

Abrir:

```text
http://127.0.0.1:8000
```

También:

```text
http://localhost:8000
```

---

## Usuarios de prueba

Los usuarios de prueba son creados mediante `DatabaseSeeder`.

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

El usuario `Sofia` tiene un cliente asociado mediante el seeder.

Estas credenciales son únicamente para desarrollo y pruebas.

---

## Flujo recomendado de prueba

### Administrador

1. Iniciar sesión con `Maestro`.
2. Consultar clientes.
3. Crear o editar clientes.
4. Crear un empleado.
5. Crear un crédito.
6. Verificar el cálculo del total.
7. Verificar el saldo inicial.
8. Registrar un pago parcial.
9. Verificar la actualización del saldo.
10. Registrar el pago restante.
11. Verificar que el crédito cambie a `Pagado`.
12. Consultar el historial de pagos.
13. Generar o imprimir el comprobante.

### Empleado

1. Iniciar sesión con `Daniel`.
2. Consultar clientes.
3. Consultar y registrar créditos.
4. Consultar pagos.
5. Registrar pagos.
6. Comprobar que no pueda acceder al módulo de empleados.

### Cliente

1. Iniciar sesión con `Sofia`.
2. Entrar a `Mis Créditos`.
3. Consultar sus créditos.
4. Abrir uno de sus créditos.
5. Registrar un pago propio.
6. Entrar a `Mis Pagos`.
7. Consultar el historial.
8. Consultar el comprobante.
9. Verificar que no pueda acceder a información de otro cliente.

---

## Pruebas automatizadas

El proyecto incluye pruebas para:

* Autenticación.
* Cierre de sesión.
* Protección contra caché.
* Validación de DUI.
* Validación de teléfono.
* Registro de créditos.
* Monto mínimo de crédito.
* Cálculo del total.
* Cálculo del saldo.
* Pagos parciales.
* Pagos completos.
* Rechazo de pagos mayores al saldo.
* Cambio automático a `Pagado`.
* Cambio automático a `Vencido`.
* Pago propio de clientes.
* Restricción de acceso entre clientes.
* Gestión de empleados.
* Restricciones de acceso por rol.

Ejecutar:

```bash
php artisan test
```

Resultado actual:

```text
Tests:    29 passed (104 assertions)
```

---

## Verificar migraciones

```bash
php artisan migrate:status
```

---

## Reiniciar completamente la base de datos

Durante desarrollo puede utilizarse:

```bash
php artisan migrate:fresh --seed
```

### Advertencia

Este comando elimina todas las tablas y todos los datos de la base de datos configurada.

No ejecutar en una base de datos que contenga información que se necesite conservar.

---

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
├── css/
└── views/

routes/
└── web.php

tests/
├── Feature/
└── Unit/

public/
└── css/
```

---

## Principales componentes

### Modelos

* `User`
* `Role`
* `Cliente`
* `Credito`
* `Pago`

### Controladores

* `AuthController`
* `ClienteController`
* `CreditoController`
* `PagoController`
* `EmpleadoController`

### Solicitudes de validación

* `ClienteRequest`
* `CreditoRequest`
* `PagoRequest`
* `EmpleadoRequest`

### Middleware

* `CheckRole`
* `NoCacheAuthenticatedPages`

---

## Buenas prácticas utilizadas

El proyecto implementa:

* Arquitectura MVC de Laravel.
* Relaciones Eloquent.
* Migraciones.
* Seeders.
* Validaciones.
* Middleware de autorización.
* Transacciones de base de datos.
* Bloqueo de filas para actualización de saldo.
* Paginación.
* Búsqueda y filtros.
* Control de acceso por propiedad.
* Pruebas automatizadas.
* Separación entre configuración local y código versionado.

---

## Problemas comunes

### Error de conexión a MySQL

Verificar:

1. Que MySQL esté iniciado en XAMPP.
2. Que exista la base de datos `sistema_gestion_creditos`.
3. Que las credenciales del `.env` sean correctas.

Después ejecutar:

```bash
php artisan optimize:clear
```

### Error relacionado con la clave de aplicación

```bash
php artisan key:generate
```

### Error por cambios en configuración

```bash
php artisan optimize:clear
```

### Error de dependencias

```bash
composer install
```

---

## Propuesta de pruebas para compañeros

Después de instalar el proyecto se recomienda validar como mínimo:

### Clientes

* Crear un cliente.
* Editar un cliente.
* Consultar un cliente.
* Buscar un cliente.
* Desactivar y activar un cliente.
* Probar DUI inválido.
* Probar teléfono inválido.

### Créditos

* Crear un crédito.
* Probar un monto menor a `$200`.
* Crear un crédito de `$200`.
* Verificar cálculo del total.
* Verificar saldo inicial.
* Verificar fecha de vencimiento.
* Consultar créditos por estado.

### Pagos

* Registrar un pago parcial.
* Registrar el pago restante.
* Verificar cambio a `Pagado`.
* Intentar registrar un pago mayor al saldo.
* Consultar historial de pagos.
* Revisar el comprobante.
* Registrar un pago desde una cuenta Cliente.

### Empleados

* Crear un empleado.
* Editar un empleado.
* Cambiar contraseña.
* Desactivar un empleado.
* Activar un empleado.
* Verificar que un Empleado no pueda acceder al módulo de empleados.
* Verificar que un Cliente no pueda acceder al módulo de empleados.

### Seguridad

* Iniciar sesión con distintos roles.
* Verificar el acceso según el rol.
* Iniciar sesión como Cliente.
* Intentar consultar información de otro cliente.
* Cerrar sesión e intentar regresar mediante el botón Atrás.

---

## Reporte de observaciones

Si encuentras un error o comportamiento inesperado, indicar:

```text
Usuario utilizado:
Módulo:
Pasos realizados:
Resultado esperado:
Resultado obtenido:
Mensaje de error:
Captura de pantalla:
```

También son bienvenidas observaciones sobre:

* Diseño de la interfaz.
* Facilidad de uso.
* Validaciones.
* Mensajes al usuario.
* Flujo de clientes, créditos y pagos.
* Seguridad y permisos.
* Errores encontrados.
* Mejoras propuestas.

---

## Estado actual del proyecto

El sistema cuenta actualmente con:

* Autenticación.
* Tres roles: Administrador, Empleado y Cliente.
* Gestión de empleados.
* Gestión de clientes.
* Validación de DUI y teléfono.
* Gestión de créditos.
* Monto mínimo de crédito de `$200`.
* Gestión de pagos.
* Pagos propios para clientes.
* Cálculo automático de saldo.
* Cambio automático de estados.
* Historial de pagos.
* Comprobantes imprimibles.
* Restricciones de acceso por rol y propiedad.
* Protección contra caché después del cierre de sesión.
* Interfaz responsive.
* Tema oscuro.
* Pruebas automatizadas.

### Estado de pruebas

```text
29 tests passed
104 assertions
```

---

## Repositorio

Repositorio principal:

[https://github.com/sofia-GM22/Gestion-creditos-pagos](https://github.com/sofia-GM22/Gestion-creditos-pagos)

````

## 2. Prueba y guarda todo en tu rama

Después de reemplazar el README:

```powershell
php artisan test
````

Debe seguir:

```text
Tests:    29 passed (104 assertions)
```

Luego:

```powershell
git diff --check
```

Después:

```powershell
git status
```

Ahora agrega todos los cambios finales:

```powershell
git add public/css/app-custom.css resources/views/auth/login.blade.php resources/views/layouts/app.blade.php resources/views/clientes/index.blade.php resources/views/clientes/por-estado-credito.blade.php resources/views/creditos/index.blade.php resources/views/empleados/index.blade.php resources/views/home.blade.php resources/views/pagos/create.blade.php resources/views/pagos/index.blade.php resources/views/pagos/show.blade.php README.md
```

Verifica:

```powershell
git diff --cached --check
```

y:

```powershell
git diff --cached --stat
```

## 3. Commit final de nuestra rama

```powershell
git commit -m "feat: finalizar rediseño y documentacion"
```

Después súbela:

```powershell
git push origin mejoras/documento-y-rediseno
```

## 4. Integrarla a `main`

Aquí sí hacemos el paso que quieres para que tus compañeros la vean en la rama principal.

```powershell
git checkout main
```

Actualiza `main` antes de mezclar:

```powershell
git pull origin main
```

Ahora mezcla nuestra rama:

```powershell
git merge --no-ff mejoras/documento-y-rediseno -m "merge: integrar mejoras finales"
```

Y finalmente:

```powershell
git push origin main
```

Con eso el resultado final quedará en:

```text
origin/main
```

y tus compañeros podrán verlo desde la rama principal.

### Después

Para dejar tu entorno limpio y mantener nuestra regla de trabajo:

```powershell
git checkout mejoras/documento-y-rediseno
```

y:

```powershell
git status
```

No uses `git push --force` en `main`.

Si durante el `merge` aparece un conflicto, **detente ahí y pégame exactamente la salida de PowerShell**; no intentes resolverlo a ciegas.
