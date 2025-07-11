<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <strong>Laravel URL Shortener</strong> – A simple, rate-limited URL shortener with stats tracking, built with Laravel.
</p>

---

## 🔗 About the Project

This project is a **lightweight URL shortener** built using Laravel. It allows users to shorten long URLs and track click statistics, with rate limiting for anonymous users.

### ✨ Features

* Shorten long URLs
* Redirect to original links
* Click tracking (date, IP, user-agent)
* Rate limiting via custom middleware
* RESTful API endpoints
* Deployed Live: [url.masuonline.org](http://url.masuonline.org/)

---

## 📁 Project Structure

```text
url_shortener/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── UrlController, StatsController, SiteController, RedirectController
│   │   ├── Requests/ → ShortenUrl
│   │   └└ Middleware/ → RateLimitMiddleware
│   ├── Models/ → Url, UrlClick
│   ├── Services/ → UrlShortener
│   └└ Exceptions/ → InvalidUrlException
├── database/
│   ├── migrations/
│   └└ seeders/ → UrlSeeder
├── routes/ → api.php, web.php
├── README.md
```

---

## 🚀 How to Clone and Run

Follow these steps to set up and run the Laravel URL Shortener locally using SQLite:

### ✅ Requirements

* PHP >= 8.1
* Composer
* SQLite (no MySQL or npm required)

### 🧾 Steps

1. **Clone the Repository**

```bash
git clone https://github.com/Ojay01/link_shortener
cd link_shortener
```

2. **Install PHP Dependencies**

```bash
composer install
```

3. **Set Up Environment File**

```bash
cp .env.example .env
```


4. **Create the SQLite Database File**

```bash
touch database/database.sqlite
```

5. **Generate Application Key**

```bash
php artisan key:generate
```

6. **Run Migrations**

```bash
php artisan migrate
```

7. **Seed the Database (Optional)**

```bash
php artisan db:seed
```

> This seeds example data using `UrlSeeder`.

8. **Serve the Application**

```bash
php composer run dev
```

Visit the app at: [http://localhost:8000](http://localhost:8000)

---

## 🥪 API Endpoints

| Method | Endpoint          | Description                |
| ------ | ----------------- | -------------------------- |
| POST   | `/api/shorten`    | Shorten a URL              |
| GET    | `/api/stats/{id}` | Get stats for a short link |

---
