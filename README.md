# Organizer

___Данный органайзер состоит из нескольких независимых приложений___

### __Методы разработки__


Данное приложение разработано на фреймворке Laravel 8-ой версии с использованием фронтендной части на фреймворке Vue 2-ой версии.

Для авторизации использован пакет laravel/passport



### __Описание__

### Reminder

Приложение Reminder напоминает пользователю о приближающихся событиях , которые повторяются каждый год или месяц (дни рождения, платежи за страховку, квартиру, интернет и т.д.) Так же в приложение можно задать уникальное событие, которое удаляется из базы после потери его актуальности при нажатии зеленой кнопки.

Приложение состоит из двух страниц: 

1. ___Home___ - содержит четыре таблицы:
- "просроченные",
- "сегодня",
- "завтра",
- "на неделе".

    В этих таблицах можно удалять события красной кнопкой, редактировать синей, а так же переносить события на следующий месяц или год при нажатии на зеленую кнопку с галочкой. Если событие установлено как "unique", при нажатии на зеленую кнопку будет удалено.

2. ___Events___ - содержит одну таблицу со всеми событиями.
На этой странице можно просмотреть все события и добавить новое, отредактировать или удалить.

### Todo-list

Приложение To-do list помогает пользователю составить и сохранить список задач.

Приложение состоит из одной таблицы: 

- В этой таблице можно удалять задачи красной кнопкой, сортировать по важности, присвоив задаче рейтинг от 0 до 5, зачеркивать выполненные. 

### Jobs

Неуверен что данное приложение будет кому то интересно, но мне лично захотелось создать его, т.к начинаю поиски первой работы и хочу вести журнал об общении и результатах с IT-компаниями. Насколько я знаю что первую работу новичку найти очень трудно и попыто будет много. Это приложение мне поможет не запутаться в каких компаниях я делал тесты и был на собеседованиях.

Приложение состоит из двух страниц: 

1. ___Companies___ - содержит список компаний и кнопки для создания новых, редактирования имеющихся и удаления.

    При клике на одну из компаний открывается отдельная страница "Company".

2. ___Company___ - содержит название, описание компании, а также список тестов или собеседований прошедших в данной компании. В списке также имеются кнопки добавления, редактирования и удаления тестов или собеседований.

### __Установка__

 - Склонируйте репозиторий
 - установите зависимости командой:  
    ```
    composer install
    ```
 - Создайте базу данных и выполните миграцию таблиц командой:
    ```
    php artisan migrate
    ```
 - Разместите все файлы на выбранном вами хостинге.


### __Использование__

Для регистрации в приложении выполните следующие действия: 
 - Нажмите "register" на панели навигации.
 - Введите ваше Имя, E-mail, пароль и нажмите "Save".
 - После регистрации откроется форма "Login", где введите ваши E-mail и пароль.

Если вы уже зарегистрированы, для начала пользования приложением:
 - Нажмите "login" на панели навигации.
 - Введите в открывшейся форме ваш email и пароль.
 - Для выхода из учетной записи нажмите "logout"

Чтобы создать событие для напоминания:
 - Нажмите "Events" на панели навигации.
 - В открывшейся странице нажмите "new Event".
 - В открывшейся форме заполните все поля и нажмите "Save".

 Чтобы создать новую задачу:
 - Нажмите "To-do list" на панели навигации.
 - Введите название задачи в поле и нажмите "Enter".
 - Для редактирования задачи, нажмите на нее и отредактируйте, после чего нажмите "Enter".

 Чтобы открыть список компаний:
 - Нажмите "Companies" на панели навигации.
 - В открывшейся странице нажмите "new Company" чтобы добавить компанию в список.
 - Кликните название компании и в открышвейся странице нажмите "new Test" чтобы добавить тест или собеседование в список.
 - Кликние название теста или собеседования чтобы посмотреть описание.

Для максимально эфективного использования данного приложения рекомендуется: 
 - Cоздать файл с расширением .html и поместить в него следующимй код:

    ```
    <!doctype html>
    <html lang="ru">
    <head>
        <meta charset="utf-8" />
        <title></title>
        <link rel="stylesheet" href="style.css" />
        <script language="JavaScript" ENGINE="text/javascript">
            location="Your website"
        </script>
    </head>
    <body>
    ...    
    </body>
    </html>
    ```
   В качестве значения ключа location поставьте ссылку на адрес где находится ваш сайт "Reminder"

 - Поместите этот файл в папку "Автозагрузка" на вашем компьютере.


  ___Поздравляю!___
  
  ___Теперь после включения компьютера, ваше приложение Reminder будет автоматически запускаться и выводить напоминания на ваш экран.___