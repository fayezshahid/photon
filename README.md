# Photo Management System

Photon, a robust photo management platform built with PHP Laravel and MySQL. Features dynamic client-side interactions for favoriting, archiving, and sharing photos without page reloads.

## 🛠️ Technologies Used

- **PHP Laravel** - Backend framework
- **MySQL** - Database management
- **Eloquent ORM** - Database handling
- **jQuery** - Client-side interactions
- **XAMPP** - Local development environment

## 🚀 Getting Started

### Prerequisites

1. **Install XAMPP**
   - Download from [https://www.apachefriends.org/](https://www.apachefriends.org/)
   - Install and launch XAMPP Control Panel
   - **Enable Apache and MySQL** from the control panel

2. **Install Node.js and Composer**
   - Download Node.js from [https://nodejs.org/](https://nodejs.org/)
   - Download Composer from [https://getcomposer.org/](https://getcomposer.org/)

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/fayezshahid/photon.git
   cd photon
   ```

2. **Environment Setup**
   ```bash
   # Create .env file and copy content from .env.example
   cp .env.example .env
   ```

3. **Database Setup**
   - Open phpMyAdmin (http://localhost/phpmyadmin) or whatever procedure you are familiar with to create a database in MySQL
   - Create a new database for your project
   - Update `.env` file with your database details:
   
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_local_db_name
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Install Dependencies**
   ```bash
   # Install PHP dependencies
   composer install
   
   # Install Node.js dependencies
   npm install
   ```

5. **Laravel Setup**
   ```bash
   # Generate application key
   php artisan key:generate
   
   # Run database migrations
   php artisan migrate
   
   # Start the development server
   php artisan serve
   ```

6. **Access the application**
   - Open your browser and go to `http://localhost:8000`

## 🎯 Features

- **Dynamic Photo Management**: Upload, organize, and manage photos
- **Real-time Interactions**: Favorite, archive, and share without page reloads
- **Efficient Database Handling**: Eloquent ORM for optimized queries
- **Responsive UI**: jQuery-powered smooth user experience

## 📁 Project Structure

```
photo-management-platform/
│
├── app/                    # Laravel application logic
├── database/              # Migrations and seeders
├── public/                # Public assets
├── resources/             # Views and frontend assets
├── routes/                # Application routes
├── .env.example           # Environment configuration template
└── README.md             # Documentation
```

## 🔧 Key Functionalities

- **Photo Upload & Storage**: Local storage that can be upgraded to cloud
- **Dynamic Actions**: jQuery-powered favoriting, archiving, sharing
- **Database Management**: Efficient data handling with Eloquent ORM
- **User Management**: Secure authentication and authorization