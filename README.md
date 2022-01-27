<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

-   [Simple, fast routing engine](https://laravel.com/docs/routing).
-   [Powerful dependency injection container](https://laravel.com/docs/container).
-   Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
-   Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
-   Database agnostic [schema migrations](https://laravel.com/docs/migrations).
-   [Robust background job processing](https://laravel.com/docs/queues).
-   [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

### Instalación

1. Clonar el repositorio

```bash
git clone https://github.com/FrederST/reto-desarrolladores-php.git
```

2. Instalar dependencias del backend:

```bash
$ composer install
```

3. Generar archivo .env para configuración de las variables de entorno:

```bash
$ cp .env.example .env
```

> Ahora debemos configurar la base de datos en phpMyAdmin y en las variables de entorno que se encuentran en el archivo .env generado anteriormente. En este archivo también debemos configurar las credenciales de Mailtrap para probar la funcionalidad de verificación dle email del usuario.

4. Generar la llave de la aplicación:

```bash
$ php artisan key:generate
```

5. Migraciones y alimentación de la base de datos:

```bash
$ php artisan migrate --seed
```

6. Dependencias del frontend y construcción de assets:

```bash
$ npm install
$ npm run dev
```

-   Despliegue:

```bash
$ php artisan serve
```

-   Ahora puedes ver el despliegue en la url: http://localhost:8000/

Nota:Para la ejecución de los jobs debes abrir una nueva terminal y ejecutar el siguiente comando:

```bash
$ php artisan queue:work
```

## Accesos.

| Email         | Password |
| ------------- | -------- |
| test@test.com | password |

---

## API

para el uso del API debes habilitar tu token en el apartado de configuraciones

| Header          | Type     | Description                  |
| :-------------- | :------- | :--------------------------- |
| `Authorization` | `string` | **Required**. Your API Token |

#### Get all products

```http
  GET /api/v1/products
```

| Parameter    | Type     | Description                |
| :----------- | :------- | :------------------------- |
| `name`       | `string` | Filtro por nombre          |
| `sale_price` | `string` | Filtro por precio de venta |

#### Get Product

```http
  GET /api/v1/products/${id}
```

| Parameter | Type     | Description                           |
| :-------- | :------- | :------------------------------------ |
| `id`      | `string` | **Required**. Id del product a buscar |

#### Crear Producto

```http
  POST /api/v1/products
```

| Type   | Description                                    |
| :----- | :--------------------------------------------- |
| `json` | **Required**. informacion del producto a crear |

#### Actualiar Producto

```http
  GET /api/v1/products/${id}
```

| Type   | Description                                         |
| :----- | :-------------------------------------------------- |
| `json` | **Required**. informacion del producto a actualizar |

#### Desactivar Producto

```http
  PUT /api/v1/products/disable/${id}
```

| Parameter | Type     | Description                                |
| :-------- | :------- | :----------------------------------------- |
| `id`      | `string` | **Required**. Id del producto a desactivar |

#### Eliminar Producto

```http
  DELETE /api/v1/products/${id}
```

| Parameter | Type     | Description                              |
| :-------- | :------- | :--------------------------------------- |
| `id`      | `string` | **Required**. Id del producto a eliminar |

#### Importar Productos

```http
  POST /api/v1/products/import
```

| Parameter  | Type   | Description                                        |
| :--------- | :----- | :------------------------------------------------- |
| `products` | `file` | **Required**. archivo csv con productos a importar |

#### Product Request

```json
{
    "code": "0000000002" (opcional),
    "name": "Product 2",
    "description": "Desciption 2",
    "quantity": 21,
    "weight_unit_id": 1,
    "price": 1000,
    "sale_price": 2000
}
```

#### Product Repose

```json
{
    "id": 5,
    "code": "0000000005",
    "name": "Product 5",
    "description": "Desciption 5",
    "quantity": 21,
    "weight": {
        "value": "20.00",
        "unit": "kg"
    },
    "price": {
        "value": 10000,
        "currency": "USD"
    },
    "sale_price": {
        "value": 20000,
        "currency": "USD"
    },
    "disabled_at": null,
    "created_at": "2022-01-24T08:16:10.000000Z",
    "updated_at": "2022-01-27T05:12:20.000000Z",
    "images": []
}
```
