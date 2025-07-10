# Peluquería Anita

Una aplicación de gestión de clientes, citas y atenciones para **Peluquería Anita**, construida con:

- **Laravel 12** + **Breeze** (Autenticación)  
- **Inertia.js** + **Vue 3** (SPA híbrido)  
- **Vite** (bundler / dev server)  
- **Tailwind CSS** (estilos)  
- **MySQL** (base de datos)  
- **Docker** (contenedor de producción/local)  

---

## 🔧 Requisitos

- Docker  
- Un archivo `.env` con tu configuración de entorno  

---

## ⚙️ Configuración

1. Clona este repositorio y sitúate en la raíz del proyecto.

2. Crea un archivo `.env` con el siguiente contenido mínimo:

   ```dotenv
   APP_URL=localhost
   #ASSET_URL=localhost
   APP_DEBUG=true
   APP_ENV=local

   SANCTUM_STATEFUL_DOMAINS=localhost:10000
   CACHE_DRIVER=file
   SESSION_DOMAIN=localhost

   # Ruta al certificado SSL (rellenado por start.sh desde MYSQL_ATTR_SSL_CA_B64)
   MYSQL_ATTR_SSL_CA=/usr/src/app/storage/certs/ca.pem

   APP_KEY=base64:TU_APP_KEY_AQUI=

   DB_CONNECTION=mysql
   DB_HOST=TU_HOST
   DB_PORT=TU_PUERTO
   DB_DATABASE=defaultdb
   DB_USERNAME=TU_USUARIO
   DB_PASSWORD=TU_CONTRASEÑA

   # Usuario de Pruebas generadoen la migracion insert_test_admin_user
   ADMIN_TEST_NAME="Admin Pruebas"
   ADMIN_TEST_EMAIL="admin@example.com"
   ADMIN_TEST_PASSWORD="admin1234"

   # Certificado SSL en base64 para inyectarlo vía start.sh
   MYSQL_ATTR_SSL_CA_B64=TU_CERT_BASE64
   #Nota: MYSQL_ATTR_SSL_CA_B64 debe ser tu ca.pem codificado en Base64 (sin saltos de línea)
   ```

## Base de Datos
La base de datos de este proyeto se realizo en un servidor gratuito en la nube, cada servicio en la nube otorga la correcta configuracion para conectar su aplicacion con la base de datos.
Entre estos datos se encuentra el certificado SSL.
Sin certificado SSL y la correcta configuracion hacia la base de datos, el proyecto no funcionara.
Si insisten en levantar el proyecto sin utilizar `docker`, lo cual no recomiendo.
Entonces deben crear la carpeta 'certs' dentro de la carpeta /storage y pegar el certificado de la base en formato .pem
Luego, poner la ruta relativa o absoluta de ese archivo en la variable de entorno MYSQL_ATTR_SSL_CA.

En sistemas UNIX: El sistema funciona sin este archivo, ya que se puede serializar en formato de base 64 y poner el serial en la variable de entorno MYSQL_ATTR_SSL_CA_B64.
El script start.sh se encarga de convertir ese binario en el archivo correspondiente '/storage/certs/ca.pem' (Esto se realizo para el despliegue en la nube)

## 🐳 Ejecución con Docker

Desde la raíz del proyecto:
```bash 
# 1) Construye la imagen Docker
sudo docker build -t peluqueria-anita .

# 2) Arranca el contenedor (exponiendo el puerto 10000)
sudo docker run --rm \
  --env-file .env \
  -p 10000:10000 \
  peluqueria-anita
```

1. El script start.sh dentro del contenedor:
    Decodifica MYSQL_ATTR_SSL_CA_B64 a storage/certs/ca.pem
    Corre migraciones y cachea config/rutas/vistas
    Arranca php artisan serve --host=0.0.0.0 --port=10000

## 🚀 Tecnologías utilizadas

| Capa                 | Tecnología                                     |
|----------------------|------------------------------------------------|
| **Backend**          | Laravel 12                                     |
| **Autenticación**    | Laravel Breeze                                 |
| **Full-stack SPA**   | Inertia.js + Vue 3                             |
| **Build & Dev**      | Vite                                           |
| **CSS**              | Tailwind CSS                                   |
| **Bundling & Assets**| laravel-vite-plugin, @vitejs/plugin-vue        |
| **DB**               | MySQL (+ SSL CA injectado por Docker)          |
| **Contenedores**     | Docker (PHP 8.2-FPM, Node.js 18)               |

## 📒 Flujo de trabajo
1. El frontend (Vue) y backend (Laravel) comparten un solo servidor dev via Vite + Inertia.
2. Las rutas Inertia cargan componentes .vue con datos de Eloquent.
3. Breeze provee login/registro; tablas CRUD con TableCrud.vue.
4. Docker empaqueta PHP, Node y dependencias para producción o testing local.
