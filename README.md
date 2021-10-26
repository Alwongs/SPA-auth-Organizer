# Процесс разработки приложения Organizer
Laravel 8 + Vue 2



## Бэкенд (api)


#### - ___Загрузка проекта Laravel___

```
composer create-project laravel/laravel example-app
```

#### - ___Создание таблиц___

```
php artisan make:model Event -mf

```

#### - ___Добавление полей и миграция___

```
php artisan migrate
```

#### - ___Создание Api Контроллера___

```
php artisan make:controller Api/EventController --api
```

#### - ___Создание Api маршрута___

```
Route::apiResources([
    'events' => EventController::class,
]);
```
#### - ___Создание Ресурса EventResource___

```
php artisan make:resource EventResource
```

#### - ___Добавление EventStoreRequest___


```
php artisan make:request EventStoreRequest
```
#### - ___Добавление версий___

https://www.youtube.com/watch?v=cXXWiWFnHXE&list=PLze7bMjv1CYv7JNFtFjs1jqE5bW5WHQDP&index=6


## Фронтенд (Vue.js)

#### - ___Подключение Vue___


```
composer require laravel/ui
php artisan ui bootstrap
php artisan ui vue
npm install && npm run dev
```
при ошибке:

```
npm update vue-loader
```

и снова:

```
npm run dev
```

или: 

```
npm run watch
```
В главном макете подключение к файлу app.js должно быть в конце тега ```<body>```

#### - ___Регистрация главного компонента в файле app.js___

```
Vue.component('app', require('./components/App').default);
```

#### - ___Установка роутера___

```
npm install vue-router
```

#### - ___Установка валидатора vuelidate

```
npm install vuelidate --save
```

#### - ___Добавление нового столбца в существующую таблицу


php artisan make:migration add_importance_to_tasks_table --table=tasks