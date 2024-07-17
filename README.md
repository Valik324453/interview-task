!!!Проект запускается командой "docker-compose up" !!!


Далее прилагаю ТЗ, в котором, знаком "+" отмечены выполненные пункты

# Junior Back-end Developer (TT)

**Задача:**

Cделать HTTP сервис для сокращения URL наподобие [Bitly](https://bitly.com/) и других сервисов.

Cделать JSON API сервис. Frontend либо React/Vue либо JQuery/Ajax. Frontend Framework использовать любой (Bootstrap, Tailwind и тд.)
К дизайну требовании нет.

**Должна быть возможность:**

+ сохранить короткое представление заданного URL
+ перейти по сохраненному ранее короткому представлению и получить redirect на соответствующий исходный URL
+ через админку просматривать добавленные ссылки и кол-во переходов по ним
+ регистрация пользователей (админ может просматривать все ссылки а обычные пользователи только свои. Данные админа: **admin login: admin, password: qweqwe**) с полями:	Имя/Фамилия	Логин (валидация на уникальность)	Пароль	Повтор пароля (валидация на совпадение с паролем)
+ страница входа (логин, пароль)

**Требования к ТЗ:**

+ Язык программирования: PHP/Node JS
+ Предоставить инструкцию по запуску приложения. В идеале (но не обязательно) – использовать контейнеризацию с возможностью запустить проект командой *docker-compose up*
+ Требований к используемым технологиям нет - можно использовать любую БД для персистентности
+ Код нужно выложить на github (просьба не делать форк этого репозитория, чтобы не плодить плагиат)

**Будет большим преимуществом (не обязательно):**

- Написать тесты (постарайтесь достичь покрытия в 70% и больше)
+ Добавить валидацию URL с проверкой корректности ссылки
+ Добавить возможность задавать кастомные ссылки, чтобы пользователь мог сделать их человекочитаемыми - http://bit.ly/example_link
- Провести нагрузочное тестирование с целью понять, какую нагрузку на чтение может выдержать вас сервис
- Если вдруг будет желание, выложить сервис на бесплатный хостинг - Google Cloud, AWS и подобные.
