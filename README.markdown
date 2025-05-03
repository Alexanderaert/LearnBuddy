# LearnBuddy.loc API

**LearnBuddy.loc** — это мощное API на базе Laravel, созданное для платформы, которая соединяет учеников и учителей. Наша цель — упростить поиск идеального наставника для изучения новых навыков, подготовки к экзаменам или углубленного изучения любимого предмета. API предоставляет гибкие и безопасные эндпоинты для управления пользователями, поиском учителей, бронированием уроков и многим другим.

## 🚀 Основная идея

LearnBuddy.loc помогает:
- **Ученикам** находить учителей по предметам, уровню подготовки, цене и доступности.
- **Учителям** предлагать свои услуги, управлять расписанием и взаимодействовать с учениками.
- Обеспечивать **удобный поиск**, фильтрацию и коммуникацию через RESTful API.

С LearnBuddy.loc образование становится доступным и персонализированным!

## ✨ Основные функции

- **Поиск учителей**: Фильтрация по предметам, рейтингу, цене и расписанию.
- **Профили пользователей**: Подробные данные об учителях (опыт, образование, отзывы).
- **Бронирование уроков**: Создание и управление расписанием с уведомлениями.
- **Отзывы и рейтинги**: Система обратной связи для повышения доверия.
- **Аутентификация**: Безопасная авторизация через JWT.
- **Масштабируемость**: Поддержка интеграции с веб- и мобильными приложениями.

## 🛠 Технологии

- **Backend**: Laravel 10 (PHP 8.1+) — надежный фреймворк для RESTful API.
- **Database**: MySQL/PostgreSQL для хранения данных пользователей, уроков и транзакций.
- **Authentication**: JWT (JSON Web Tokens) для безопасного доступа к API.
- **API Documentation**: Swagger/OpenAPI (рекомендуется для документирования).
- **Тестирование**: PHPUnit для unit и feature тестов.
- **Дополнительно**: Docker для контейнеризации, Laravel Sanctum для аутентификации (опционально).

## 📋 Установка

Следуйте этим шагам, чтобы запустить API локально:

### Предварительные требования
- PHP >= 8.1
- Composer
- MySQL/PostgreSQL
- Git
- (Опционально) Docker для контейнеризации

### Шаги
1. **Клонируйте репозиторий**:
   ```bash
   git clone https://github.com/your-username/LearnBuddy.loc.git
   cd LearnBuddy.loc
   ```

2. **Установите зависимости**:
   ```bash
   composer install
   ```

3. **Настройте окружение**:
   - Скопируйте `.env.example` в `.env`:
     ```bash
     cp .env.example .env
     ```
   - Настройте параметры базы данных, JWT и другие переменные в `.env`:
     ```env
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=learnbuddy
     DB_USERNAME=root
     DB_PASSWORD=
     ```

4. **Сгенерируйте ключ приложения**:
   ```bash
   php artisan key:generate
   ```

5. **Установите JWT (если используется)**:
   ```bash
   php artisan jwt:secret
   ```

6. **Выполните миграции**:
   ```bash
   php artisan migrate
   ```

7. **Запустите сервер**:
   ```bash
   php artisan serve
   ```
   API будет доступно по адресу `http://localhost:8000`.

## 🔌 Использование API

API предоставляет RESTful эндпоинты для взаимодействия с платформой. Все запросы должны содержать заголовок `Accept: application/json`.

### Аутентификация
- **Регистрация**: `POST /api/register`
- **Логин**: `POST /api/login`
- **Выход**: `POST /api/logout` (требуется токен)

### Примеры эндпоинтов
1. **Поиск учителей**:
   ```http
   GET /api/teachers?subject=math&level=beginner&price_max=50
   ```
   **Ответ**:
   ```json
   [
       {
           "id": 1,
           "name": "John Doe",
           "subject": "Mathematics",
           "experience": "5 years",
           "rating": 4.8,
           "price_per_hour": 40
       }
   ]
   ```

2. **Бронирование урока**:
   ```http
   POST /api/bookings
   Authorization: Bearer {token}
   ```
   **Тело запроса**:
   ```json
   {
       "teacher_id": 1,
       "date": "2025-05-10",
       "time": "14:00"
   }
   ```

3. **Получение профиля учителя**:
   ```http
   GET /api/teachers/1
   ```
   **Ответ**:
   ```json
   {
       "id": 1,
       "name": "John Doe",
       "bio": "Experienced math teacher...",
       "subjects": ["Mathematics", "Algebra"],
       "reviews": [
           {
               "rating": 5,
               "comment": "Great teacher!"
           }
       ]
   }
   ```

### Документация API
Для полной документации эндпоинтов используйте Swagger/OpenAPI (рекомендуется установить пакет, например, `laravel-swagger`):
```bash
composer require darkaonline/l5-swagger
php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"
php artisan l5-swagger:generate
```

## 🧪 Тестирование

Для запуска тестов:
```bash
php artisan test
```

Тесты включают:
- Unit тесты для моделей и сервисов.
- Feature тесты для проверки эндпоинтов API.

## 🚧 Дорожная карта

- Добавить поддержку уведомлений (email, push).
- Реализовать интеграцию с платежными системами (Stripe, PayPal).
- Добавить систему рекомендаций учителей на основе предпочтений.
- Разработать фронтенд (React/Vue.js) для взаимодействия с API.
- Поддержка мультиязычности для глобального доступа.

## 🤝 Контрибьютинг

Мы приветствуем любые вклады в развитие LearnBuddy.loc! Чтобы внести изменения:
1. Форкните репозиторий.
2. Создайте новую ветку (`git checkout -b feature/awesome-feature`).
3. Внесите изменения и закоммитьте (`git commit -m 'Add awesome feature'`).
4. Запушьте изменения (`git push origin feature/awesome-feature`).
5. Создайте Pull Request.

## 📬 Контакты

Если у вас есть вопросы или предложения, свяжитесь с нами:
- Email: support@learnbuddy.loc
- GitHub Issues: [Создать issue](https://github.com/your-username/LearnBuddy.loc/issues)

## 📝 Лицензия

Проект распространяется под лицензией MIT. Подробности в файле [LICENSE](LICENSE).

---

**LearnBuddy.loc** — найди своего учителя, начни учиться уже сегодня!