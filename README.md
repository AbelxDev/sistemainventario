<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# PASOS PARA LEVANTAR EL PROYECTO

1.- Clonar repositorio

2.- Ejecutar comando dentro de la carpeta para instalar dependencias del back-end
```
composer install
```

2.1.- Ejecutar comando dentro de la carpeta para instalar dependencias del front-end
```
npm install
```

3.- Clonar el archivo```.env.example``` a ```.env```, puedes hacerlo con el siguiente codigo:
```
cp .env.example .env
```

4.- Generar ```key``` del proyecto
```
php artisan key:generate
```

5.- Migrar tablas en base de datos
```
php artisan migrate 
```

6.- Ejecutar semilla
```
php artisan db:seed
```

7.- Levantar backend
```
php artisan serve
```

8.- Levantar frontend
```
npm run dev
```


