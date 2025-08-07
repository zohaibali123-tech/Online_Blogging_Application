# 📝 Laravel Blogging System

This is a complete blogging platform built with **Laravel 11**, featuring user authentication, role-based access (Admin/User), blog/category/post management, file attachments, and AJAX-powered dynamic UI — all styled with Bootstrap 5.

---

## 📌 Features

- Admin & User Role-Based Access
- User Registration and Login
- Blog and Category Management (Create, Edit, Delete)
- Post Creation with File Attachments
- Activate/Deactivate (Status Toggle via AJAX)
- Live Search and Pagination
- Responsive UI using Bootstrap 5
- Clean code structure with custom controllers

---

## 🚀 How to Run This Project Locally

### 1. Clone the Repository

```bash
git clone https://github.com/your-username/your-repo-name.git
cd your-repo-name
```

### 2. Install Dependencies

```bash
composer install
npm install && npm run dev
```

### 3. Configure Environment File

```bash
cp .env.example .env
php artisan key:generate
```

Then open `.env` and set your database credentials:

```
DB_DATABASE=your_db_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 4. Run Database Migrations

```bash
php artisan migrate
```

### 5. Start the Development Server

```bash
php artisan serve
```

Visit [http://localhost:8000](http://localhost:8000) in your browser.

---

## 👨‍💻 Admin Access (Optional)

If you manually insert an admin user into the `users` table:
- Set `role_id = 1` to make the user an admin
- Login from `/login` route

---

## 📁 Project Structure (Highlights)

```
app/
├── Http/Controllers/     # AuthController, BlogController, etc.
├── Models/               # Blog, Category, Post, etc.
resources/views/
├── admin/                # Admin dashboard views
├── auth/                 # Login/Register views
public/js/                # jQuery and custom scripts
routes/web.php            # All web routes
```

---

## ⚙️ Email (Log Driver)

This project uses `log` mail driver. Configure it in `.env` like this:

```
MAIL_MAILER=log
```

To view sent emails:

```bash
tail -f storage/logs/laravel.log
```

---

## 📦 Tech Stack

- **Backend:** Laravel 11, MySQL
- **Frontend:** Blade, Bootstrap 5, jQuery (AJAX)
- **Authentication:** Custom AuthController with role-based redirects
- **Storage:** File attachments via `public/storage`
- **Middleware:** Custom Role Middleware

---

## 🙋‍♂️ Author

**Zohaib Ali**  
GitHub: [github.com/your-username](https://github.com/zohaibali123-tech)  
LinkedIn: [linkedin.com/in/your-profile](https://linkedin.com/in/zohaib-baloch)

---

## 🪪 License

This project is open-sourced under the [MIT License](LICENSE).
