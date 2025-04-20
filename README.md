<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<h1>Guía para Correr el Proyecto Laravel Turismo Web(Con Migraciones y Seeders)</h1>

<p>A continuación, se describen los pasos necesarios para poner a funcionar un proyecto Laravel clonado con migraciones y seeders. Sigue estos pasos y tendrás el proyecto corriendo en tu entorno local.</p>

<h2>1. Clonar el Proyecto</h2>
<p>Primero, debes clonar el proyecto desde el repositorio de Git. Abre tu terminal y ejecuta el siguiente comando:</p>

<pre><code>git clone &lt;https://github.com/Drewpl2021/Backend-project-capachica.git&gt;</code></pre>

<p>Reemplaza &lt;URL del repositorio&gt; con la URL del repositorio que deseas clonar.</p>

<h2>2. Acceder a la Carpeta del Proyecto</h2>
<p>Una vez que hayas clonado el proyecto, navega a la carpeta del proyecto utilizando el siguiente comando:</p>

<pre><code>cd cd nombre-del-proyecto</code></pre>

<p>Reemplaza <i>nombre-del-proyecto</i> con el nombre de la carpeta del proyecto que acabas de clonar.</p>

<h2>3. Instalar las Dependencias de PHP</h2>
<p>Laravel requiere que instales las dependencias de PHP usando <a href="https://getcomposer.org/" target="_blank">Composer</a>. Si aún no tienes Composer instalado, puedes descargarlo desde allí. Luego, ejecuta el siguiente comando para instalar las dependencias:</p>

<pre><code>composer install</code></pre>

<p>Este comando descargará todas las librerías necesarias para que el proyecto funcione correctamente.</p>

<h2>4. Copiar el Archivo .env</h2>
<p>Laravel utiliza un archivo .env para manejar las configuraciones de entorno (como base de datos, claves de aplicación, etc.). Si no tienes el archivo .env en tu proyecto clonado, debes copiar el archivo .env.example y renombrarlo a .env:</p>

<pre><code>cp .env.example .env</code></pre>

<h2>5. Configurar las Variables en .env</h2>
<p>Abrir el archivo .env en tu editor de texto favorito y configurar las variables necesarias para tu entorno de desarrollo, como la base de datos. Aquí te muestro un ejemplo de configuración para una base de datos MySQL:</p>

<pre><code>DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_de_tu_base_de_datos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña</code></pre>

<p>Asegúrate de cambiar <i>nombre_de_tu_base_de_datos</i>, <i>tu_usuario</i> y <i>tu_contraseña</i> por los valores correctos de tu base de datos.</p>

<h2>6. Generar la Clave de la Aplicación</h2>
<p>Laravel necesita una clave única para la aplicación. Puedes generar esta clave con el siguiente comando:</p>

<pre><code>php artisan key:generate</code></pre>

<p>Este comando actualizará automáticamente el archivo .env con la clave generada.</p>

<h2>7. Ejecutar las Migraciones</h2>
<p>Las migraciones en Laravel crean las tablas necesarias en la base de datos. Si el proyecto ya tiene migraciones configuradas, ejecuta el siguiente comando para crear las tablas:</p>

<pre><code>php artisan migrate</code></pre>

<p>Si alguna vez necesitas borrar y volver a ejecutar las migraciones (por ejemplo, si hay cambios en las migraciones), puedes usar:</p>

<pre><code>php artisan migrate:refresh</code></pre>

<h2>8. Ejecutar los Seeders</h2>
<p>Los seeders permiten poblar las tablas de la base de datos con datos predeterminados o de prueba. Si el proyecto tiene seeders configurados, puedes ejecutar el siguiente comando para llenarlas con datos:</p>

<pre><code>php artisan db:seed</code></pre>

<p>Si solo deseas ejecutar un seeder específico, usa:</p>

<pre><code>php artisan db:seed --class=NombreDelSeeder</code></pre>

<h2>9. Iniciar el Servidor de Desarrollo</h2>
<p>Finalmente, para ver el proyecto funcionando, debes iniciar el servidor de desarrollo de Laravel. Puedes hacer esto ejecutando el siguiente comando:</p>

<pre><code>php artisan serve</code></pre>

<p>Esto levantará el servidor en <code>http://127.0.0.1:8000</code>. Ahora puedes abrir tu navegador y verificar que la API esté corriendo correctamente.</p>

<h2>Resumen de Pasos</h2>

<ol>
  <li><strong>Clonar el proyecto:</strong><br>
    <pre><code>git clone &lt;URL del repositorio&gt;</code></pre>
  </li>
  <li><strong>Acceder al proyecto:</strong><br>
    <pre><code>cd nombre-del-proyecto</code></pre>
  </li>
  <li><strong>Instalar dependencias con Composer:</strong><br>
    <pre><code>composer install</code></pre>
  </li>
  <li><strong>Copiar el archivo .env:</strong><br>
    <pre><code>cp .env.example .env</code></pre>
  </li>
  <li><strong>Configurar las variables de base de datos en .env.</strong></li>
  <li><strong>Generar la clave de la aplicación:</strong><br>
    <pre><code>php artisan key:generate</code></pre>
  </li>
  <li><strong>Ejecutar las migraciones:</strong><br>
    <pre><code>php artisan migrate</code></pre>
  </li>
  <li><strong>Ejecutar los seeders:</strong><br>
    <pre><code>php artisan db:seed</code></pre>
  </li>
  <li><strong>Iniciar el servidor de desarrollo:</strong><br>
    <pre><code>php artisan serve</code></pre>
  </li>
</ol>

<p>¡Con estos pasos, tu proyecto Laravel debería estar funcionando correctamente como una API REST! Si tienes algún problema o necesitas más ayuda, no dudes en preguntar.</p>

