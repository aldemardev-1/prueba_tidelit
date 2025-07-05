# 📚 Prueba Técnica: Plataforma de Libros y Reseñas

Este proyecto es una implementación de una prueba técnica que consiste en una API RESTful básica desarrollada con **Symfony 6** y un frontend simple para consumirla construido con **Vue.js 3, TypeScript y Tailwind CSS**. El objetivo es demostrar la capacidad para estructurar un proyecto limpio, trabajar con relaciones entre entidades, crear migraciones y exponer un endpoint funcional que es consumido por una interfaz de usuario moderna.

## 🚀 Características Implementadas

### Backend (Symfony 6)

* **Modelos de Entidades:**
    * **`Book`**: `id`, `title`, `author`, `published_year`.
    * **`Review`**: `id`, `book_id` (relación ManyToOne con Book), `rating` (int de 1 a 5), `comment` (text), `created_at` (datetime).
* **Doctrine ORM:** Utilizado para la definición de entidades y la gestión de migraciones de base de datos.
* **Endpoints API:**
    * `GET /api/books`: Devuelve una lista de libros con sus datos y el promedio de rating calculado eficientemente usando Doctrine DQL.
    * `POST /api/reviews`: Permite registrar una reseña para un libro. Incluye validaciones básicas (rating entre 1 y 5, `book_id` debe existir, `comment` no puede estar vacío).
* **Fixtures:** Incluye 3 libros y al menos 6 reseñas precargadas en la base de datos utilizando Doctrine Fixtures.
* **CORS:** Configurado para permitir la comunicación entre el frontend y el backend en diferentes puertos.

### Frontend (Vue.js 3 + TypeScript + Tailwind CSS)

* **Componentización:** Aplicación modularizada en componentes reutilizables (`App.vue`, `BookList.vue`, `BookCard.vue`, `AddReviewForm.vue`).
* **Composition API:** Utiliza la Composition API de Vue 3 (`<script setup>`) para una lógica de componentes organizada y reutilizable.
* **TypeScript:** Tipado estático en todo el frontend para mejorar la robustez del código y la experiencia de desarrollo.
* **Tailwind CSS:** Estilado rápido, responsivo y personalizable directamente en el marcado HTML/Vue, siguiendo un diseño limpio y moderno.
* **Cliente HTTP:** Utiliza `axios` encapsulado en un servicio (`src/services/api.ts`) para todas las interacciones con la API, incluyendo interceptores para un manejo centralizado.
* **Manejo de Estados:** Muestra estados de carga, error y mensajes de éxito/validación al usuario.

## 📂 Estructura del Proyecto (Monorepo)

```
.
├── symfony_vue_challenge/   # Directorio del Backend Symfony
│   ├── config/
│   ├── public/
│   ├── src/
│   │   ├── Controller/
│   │   │   └── ApiController.php
│   │   ├── Entity/
│   │   │   ├── Book.php
│   │   │   └── Review.php
│   │   ├── Repository/
│   │   │   ├── BookRepository.php
│   │   │   └── ReviewRepository.php
│   │   └── DataFixtures/
│   │       ├── BookFixtures.php
│   │       └── ReviewFixtures.php
│   ├── migrations/
│   ├── var/                 # Ignorado por Git (caché, logs, data.db)
│   ├── .env                 # Ignorado por Git (usar .env.dist o .env.example)
│   └── composer.json
├── my-vue-book-app/         # Directorio del Frontend Vue.js
│   ├── public/
│   ├── src/
│   │   ├── assets/
│   │   │   └── main.css     # Estilos globales de Tailwind
│   │   ├── components/
│   │   │   ├── BookCard.vue
│   │   │   ├── BookList.vue
│   │   │   └── AddReviewForm.vue
│   │   ├── services/
│   │   │   └── api.ts       # Cliente HTTP (Axios)
│   │   │   └── types/
│   │   │       └── index.ts     # Definiciones de interfaces TypeScript
│   │   ├── App.vue
│   │   └── main.ts          # Punto de entrada de Vue
│   ├── dist/                # Ignorado por Git (build de producción)
│   ├── node_modules/        # Ignorado por Git (dependencias)
│   ├── package.json
│   ├── tsconfig.json
│   ├── vite.config.ts
│   ├── tailwind.config.js
│   └── postcss.config.js
└── .gitignore               # Archivo de ignorados para Git (único para todo el monorepo)
```

## ⚙️ Instrucciones de Instalación y Ejecución

Sigue estos pasos para configurar y ejecutar ambos proyectos en tu entorno local.

### Requisitos Previos

* **PHP 8.1+** (con extensiones `php_sqlite3` y `php_pdo_sqlite` habilitadas en `php.ini`, especialmente si usas XAMPP/WAMP).
* **Composer** (gestor de dependencias de PHP).
* **Node.js (LTS recomendado)** y **npm** (gestor de paquetes de Node.js).
* **Symfony CLI** (opcional, pero recomendado para `symfony serve`).

### 1. Configuración del Backend (Symfony)

1.  **Clonar el Repositorio:**
    ```bash
    git clone https://github.com/aldemardev-1/prueba_tidelit.git
    cd prueba_tidelit
    ```

2.  **Instalar Dependencias de Composer:**
    Navega al directorio del backend:
    ```bash
    cd symfony_vue_challenge
    composer install
    ```
    * Si el comando `php bin/console make:...` no funciona, es posible que necesites instalar `MakerBundle`:
        ```bash
        composer require symfony/maker-bundle --dev
        ```

3.  **Configurar la Base de Datos:**
    El proyecto está configurado para usar **SQLite** por defecto. La base de datos se creará en `symfony_vue_challenge/var/data.db`.
    Asegúrate de que la línea `DATABASE_URL` en `symfony_vue_challenge/.env` apunte a SQLite y no haya otras líneas `DATABASE_URL` activas que apunten a PostgreSQL o MySQL.

    ```dotenv
    # symfony_vue_challenge/.env
    DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"
    ```

4.  **Ejecutar Migraciones de Base de Datos:**
    Esto creará las tablas `book` y `review` en tu base de datos SQLite.
    ```bash
    php bin/console make:migration # Si es la primera vez o has cambiado entidades
    php bin/console doctrine:migrations:migrate
    ```

5.  **Cargar Datos de Prueba (Fixtures):**
    Esto insertará 3 libros y 6 reseñas de ejemplo.
    ```bash
    php bin/console doctrine:fixtures:load
    ```
    * Si tienes problemas con `getReference()` en los fixtures, asegúrate de que en `src/DataFixtures/ReviewFixtures.php` la llamada sea `this->getReference('nombre_referencia', App\Entity\Book::class)`.

6.  **Iniciar el Servidor Symfony:**
    ```bash
    symfony serve
    ```
    El API estará disponible en `http://127.0.0.1:8000` (o el puerto que te indique Symfony CLI).

### 2. Configuración del Frontend (Vue.js)

1.  **Navegar al Directorio del Frontend:**
    Abre una **nueva terminal** y navega al directorio del frontend desde la raíz de tu monorepo:
    ```bash
    cd my-vue-book-app
    ```

2.  **Instalar Dependencias de Node:**
    ```bash
    npm install
    ```

3.  **Iniciar el Servidor de Desarrollo de Vue:**
    ```bash
    npm run dev
    ```
    El frontend estará disponible en `http://localhost:5173` (o un puerto similar que te indique Vite).

    **¡Importante!** Asegúrate de que el backend Symfony (`http://127.0.0.1:8000`) esté funcionando **antes** de iniciar el frontend, ya que este último intentará consumir el API desde esa dirección.

## 📸 Captura de Pantalla del API

![imagen de books endpoint](/imgs/books_endpoint.png)
![imagen de reviews_post](/imgs/reviews_post.png)


### Endpoint: `GET /api/books`

Puedes acceder a este endpoint directamente en tu navegador después de iniciar el servidor Symfony: `http://127.0.0.1:8000/api/books`

**Ejemplo de respuesta JSON:**

```json
[
  {
    "id": 1,
    "title": "El Arte de Programar",
    "author": "Donald Knuth",
    "published_year": 1968,
    "average_rating": 4.5
  },
  {
    "id": 2,
    "title": "Clean Code",
    "author": "Robert C. Martin",
    "published_year": 2008,
    "average_rating": 4.5
  },
  {
    "id": 3,
    "title": "Domain-Driven Design",
    "author": "Eric Evans",
    "published_year": 2003,
    "average_rating": 4.5
  }
]
```

### Vista Previa del Frontend

![imagen del frontend 1](/imgs/frontend_1.png)
![imagen del frontend 2](/imgs/frontend_2.png)

## 📈 Pregunta Opcional: Estrategias de Escalabilidad del API

Si esta aplicación tuviera que escalar a una plataforma pública con cientos de miles de libros y millones de usuarios, se necesitarían cambios significativos para garantizar rendimiento, disponibilidad y mantenibilidad. Aquí algunos puntos clave:

1.  **Base de Datos:**
    * **Migración a una DB robusta:** Pasar de SQLite a PostgreSQL o MySQL (o una base de datos NoSQL si el modelo de datos lo justifica). Son bases de datos de producción mucho más escalables, con mejor manejo de concurrencia, replicación y herramientas de monitoreo.
    * **Optimización de Consultas e Índices:** Añadir índices a columnas frecuentemente consultadas (ej. `book_id` en `reviews`) y optimizar consultas complejas. Considerar vistas materializadas para cálculos agregados (como `average_rating`) que no necesitan ser en tiempo real absoluto.
    * **Sharding/Replicación:** Distribuir los datos y la carga entre múltiples instancias de base de datos.

2.  **Caché:**
    * **Cache de Resultados de API (Full Page/Fragment Caching):** Implementar una capa de caché (ej. Redis, Memcached, Varnish) para el endpoint `GET /api/books`. Los datos de los libros y sus promedios no cambian constantemente, por lo que se pueden cachear por un tiempo determinado, invalidando el caché solo cuando se añade/modifica una reseña a un libro.
    * **Cache de Consultas Doctrine:** Utilizar las capacidades de caché de Doctrine para reducir las consultas a la base de datos.

3.  **Rendimiento del API (Symfony):**
    * **Paginación:** Es inviable devolver todos los libros de una sola vez. Implementar paginación (`limit`, `offset`, o basados en cursor) para el endpoint `/api/books`.
    * **Throttling/Rate Limiting:** Limitar el número de solicitudes que un usuario o IP puede hacer en un período de tiempo para prevenir abusos y ataques DDoS.
    * **Queues (Colas de Mensajes):** Para operaciones que no requieren una respuesta inmediata (ej. procesamiento de reseñas complejas, envío de notificaciones), usar colas de mensajes (RabbitMQ, Kafka, AWS SQS) para procesarlas asincrónicamente y no bloquear la respuesta del API.
    * **Serialización Eficiente:** Optimizar la serialización de las respuestas JSON para enviar solo los datos necesarios y en el formato más eficiente.

4.  **Infraestructura y Despliegue:**
    * **Balanceo de Carga:** Distribuir las solicitudes entrantes entre múltiples instancias del API para manejar un alto tráfico.
    * **Contenerización (Docker) y Orquestación (Kubernetes):** Empaquetar la aplicación en contenedores facilita el despliegue, la escalabilidad horizontal y la gestión en entornos de producción complejos.
    * **CDN (Content Delivery Network):** Para servir los activos estáticos del frontend (imágenes, CSS, JS) desde ubicaciones geográficas más cercanas a los usuarios, reduciendo la latencia.
    * **Separación de Repositorios (Polyrepo):** Aunque para esta prueba usamos un monorepo, a gran escala, separar el frontend y el backend en repositorios distintos permitiría a equipos independientes trabajar y desplegar de forma autónoma.

5.  **Seguridad y Monitoreo:**
    * **Autenticación y Autorización Robustas:** Implementar un sistema de registro de usuarios, login (OAuth2, JWT) y roles/permisos más sofisticado (ej. solo usuarios autenticados pueden dejar reseñas, administradores pueden gestionar libros).
    * **Monitoreo y Logging:** Implementar herramientas de monitoreo de rendimiento (APM) y un sistema de logging centralizado para detectar y diagnosticar problemas rápidamente.

En resumen, la escalabilidad implicaría una evolución de una arquitectura monolítica simple a una distribuida, con capas de caché, balanceo de carga, optimizaciones de base de datos y un enfoque en la resiliencia y la seguridad para manejar el volumen y la concurrencia de una plataforma pública.
