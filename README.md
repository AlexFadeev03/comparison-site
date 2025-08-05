# Comparison Site

A minimalist, modern web app for comparing products with categories, subcategories, ratings, and a responsive dashboard. 

---

## 📦 Features

- Categories, subcategories, products (CRUD)
- Ratings and voting
- Modern dashboard with recent activity
- Responsive UI (Tailwind CSS)
- Authentication, registration, password reset
- Admin role and route protection
- Filtering and search
- Custom branding (spiral logo)

---

## 🚀 Installation

1. **Clone the repository:**
   ```sh
   git clone https://github.com/AlexFadeev03/comparison-site.git
   cd comparison-site
   ```
2. **Install dependencies:**
   ```sh
   composer install
   npm install
   ```
3. **Copy the .env file:**
   ```sh
   cp .env.example .env
   ```
4. **Generate app key:**
   ```sh
   php artisan key:generate
   ```
5. **Configure your database** in `.env` (MySQL or SQLite)
6. **Run migrations and seeders:**
   ```sh
   php artisan migrate --seed
   ```
7. **Start the dev servers:**
   ```sh
   php artisan serve
   npm run dev
   ```

---

## 🛠️ Tech Stack

- Laravel 12
- Blade
- Tailwind CSS
- MySQL / SQLite

---

## 📬 Feedback

Open an Issue or Pull Request for suggestions and improvements!
