<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

# Laravel Project

A modern Laravel-based web application.

## 🚀 Getting Started

Follow these instructions to set up the project on your local machine for development and testing.

### ✅ Prerequisites

```bash
- PHP ≥ 8.2 (with extensions: pdo, mbstring, openssl, tokenizer, xml)
- Composer (Dependency manager for PHP) – https://getcomposer.org/download/
- MySQL (or compatible database)
- Node.js (for frontend assets) – https://nodejs.org/
```

---

## 📂 Project Setup

1. **Clone the Repository**

```bash
git clone https://github.com/your/repo.git
cd your-project
```

2. **Create Environment File**

Copy the example environment file and configure it as needed:

```bash
cp .env.example .env
```

3. **Install Dependencies**

```bash
composer install
npm install && npm run dev
```

4. **Generate Application Key**

```bash
php artisan key:generate
```

5. **Configure the Database**

Update the `.env` file with your database credentials:

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

6. **google auth (Oatuh) credentials**
GOOGLE_CLIENT_ID=532462179948-eac01schptr73d8vc3brn5s7kptqqrjq.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=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
GOOGLE_REDIRECT_URI=http://127.0.0.1:8000/viewer/auth/google/callback

7. **video compression config**
DELETE_ORIGINAL_AFTER_COMPRESSION=true
QUEUE_CONNECTION=database

8. **Start the Development Server**

```bash
npm run dev 
php artisan serve
php artisan queue:work
```

The application will be available at: [http://localhost:8000](http://localhost:8000)

---

## 📌 Useful Commands

```bash
# Clear Cache
php artisan cache:clear
php artisan config:cache

# Run Tests
php artisan test

# Queue Management
php artisan queue:work

# Database Seeding
php artisan db:seed

# Stop Server
Press CTRL + C to stop the server.
```

---

## 📊 Directory Structure

```bash
├── app/              # Core application logic
├── bootstrap/        # Framework bootstrap files
├── config/           # Application configuration
├── database/         # Migrations, models, and seeds
├── public/           # Webserver public directory
├── resources/        # Views and assets (CSS, JS)
├── routes/           # Application routes
└── tests/            # Unit and feature tests
```

---

## 🔐 Environment Management

Since `.env` is **not** pushed to Git, share it securely with other developers.

### How to Create a `.env` File

```bash
1. Copy .env.example to .env
2. Customize the file for your local environment
```

### Share Securely

```bash
- Use secure methods like email, password managers, or encrypted messaging.
```

---

## 🧹 Troubleshooting

```bash
# Composer Timeout
composer install --no-progress --timeout=600

# Missing vendor Directory
composer install

# Permissions Issue (Linux/macOS)
sudo chmod -R 775 storage bootstrap/cache
```

---

## 🤝 Contributing

```bash
1. Fork the repository
2. Create a feature branch: git checkout -b feature-name
3. Commit changes: git commit -m "Add new feature"
4. Push to your branch: git push origin feature-name
5. Open a Pull Request
```

---

## 📄 License

```bash
This project is licensed under the MIT License.
```

---

## 📧 Support

```bash
For any issues or questions, contact: your-email@example.com
