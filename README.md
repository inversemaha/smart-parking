# 🚗 Smart Parking System

A comprehensive, modern parking management platform built with Laravel 11 following Domain-Driven Design (DDD) architecture.

![Laravel](https://img.shields.io/badge/Laravel-11.x-red?style=for-the-badge&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css)

## 🌟 Features

### 👥 **Multi-User System**
- **🏢 Admin Panel**: Complete management dashboard for parking locations, users, and analytics
- **👤 Visitor Panel**: User-friendly interface for parking discovery, booking, and payment
- **🔐 Role-Based Access Control**: Granular permissions with admin, manager, and user roles

### 🅿️ **Parking Management**
- **📍 Location Management**: Add/manage parking locations with GPS coordinates
- **🎯 Smart Slot Allocation**: Real-time availability tracking
- **⏰ Time-based Booking**: Flexible booking duration with hourly rates
- **🔄 Dynamic Pricing**: Support for different rates based on location and time

### 💳 **Payment Integration**
- **🏦 SSLCommerz Gateway**: Secure payment processing for Bangladesh
- **💰 Multiple Payment Methods**: Cards, mobile banking, internet banking
- **📄 Digital Receipts**: Automatic invoice generation
- **🔄 Refund Processing**: Automated refund handling for cancellations

### 🌐 **Multi-Language Support**
- **🇧🇩 Bengali (বাংলা)**: Complete localization for Bangladeshi users
- **🇺🇸 English**: Full English language support
- **🔄 Dynamic Switching**: Users can switch languages on the fly

### 📱 **Modern UI/UX**
- **📱 Mobile-First Design**: Responsive design optimized for all devices
- **⚡ Fast Performance**: Optimized with Redis caching
- **🎨 Professional Interface**: Clean, modern design with Tailwind CSS

## 🏗️ Architecture

### **Domain-Driven Design (DDD)**
```
app/
├── Domains/           # Core business domains
│   ├── Admin/        # Administrative functionality
│   ├── Auth/         # Authentication & authorization
│   ├── Booking/      # Booking management
│   ├── Gate/         # Gate operations & access control
│   ├── Parking/      # Parking location management
│   ├── Payment/      # Payment processing
│   ├── User/         # User management
│   └── Vehicle/      # Vehicle registration
├── Http/             # HTTP layer (controllers, middleware, requests)
├── Jobs/             # Background job processing
├── Policies/         # Authorization policies
├── Providers/        # Service providers
└── Shared/           # Shared services and utilities
```

### **Key Architectural Benefits**
- 🔒 **Separation of Concerns**: Clear domain boundaries
- 🔧 **Maintainability**: Easy to modify and extend
- 🧪 **Testability**: Isolated components for better testing
- 📈 **Scalability**: Modular design supports growth

## 🚀 Quick Start

### Prerequisites
- PHP 8.2 or higher
- Composer 2.x
- Node.js & NPM
- MySQL 8.0+
- Redis (recommended)

### Installation

1. **Clone the repository**
```bash
git clone https://github.com/your-username/smart-parking-system.git
cd smart-parking-system
```

2. **Install dependencies**
```bash
composer install
npm install && npm run build
```

3. **Environment setup**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure database** (Update `.env` file)
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=parking_system
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. **Run migrations and seed data**
```bash
php artisan migrate --seed
```

6. **Start the development server**
```bash
php artisan serve
```

Visit `http://localhost:8000` to access the application.

## 🔧 Configuration

### Payment Gateway (SSLCommerz)
```env
SSLCOMMERZ_STORE_ID=your_store_id
SSLCOMMERZ_STORE_PASSWORD=your_store_password
SSLCOMMERZ_SANDBOX=true
```

### Cache Configuration (Redis)
```env
CACHE_DRIVER=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### Queue Configuration
```env
QUEUE_CONNECTION=redis
```

## 📚 User Guide

### For Visitors (End Users)

1. **Registration & Login**
   - Visit the homepage
   - Click "Register" to create an account
   - Verify your email/phone number
   - Login to access your dashboard

2. **Finding Parking**
   - Use the search feature to find parking locations
   - Filter by location, price, features, and availability
   - View real-time slot availability

3. **Making Bookings**
   - Select your preferred location and time slot
   - Choose your registered vehicle
   - Complete payment through SSLCommerz
   - Receive booking confirmation

4. **Managing Bookings**
   - View booking history
   - Track active bookings
   - Cancel bookings (if allowed)
   - Download receipts

### For Administrators

1. **Dashboard Overview**
   - Real-time statistics and analytics
   - Revenue tracking
   - User activity monitoring

2. **Location Management**
   - Add new parking locations
   - Configure slots and pricing
   - Set operating hours and features

3. **User Management**
   - View and manage user accounts
   - Handle support requests
   - Monitor system activity

## 🛠️ Development

### Running Tests
```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --filter=VisitorAuthTest

# Run tests with coverage
php artisan test --coverage
```

### Code Quality
```bash
# PHP CS Fixer
composer run-script fix-cs

# PHPStan analysis
composer run-script analyse

# Laravel Pint (code style)
./vendor/bin/pint
```

### Database Management
```bash
# Create new migration
php artisan make:migration create_new_table

# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Fresh migration with seeding
php artisan migrate:fresh --seed
```

## 📊 API Documentation

The system includes RESTful APIs for mobile app integration:

### Authentication Endpoints
```
POST /api/auth/login
POST /api/auth/register
POST /api/auth/logout
POST /api/auth/refresh
```

### Parking Endpoints
```
GET /api/parking/locations
GET /api/parking/locations/{id}
GET /api/parking/search
POST /api/parking/availability
```

### Booking Endpoints
```
GET /api/bookings
POST /api/bookings
GET /api/bookings/{id}
PUT /api/bookings/{id}
DELETE /api/bookings/{id}
```

## 🔒 Security Features

- **🛡️ CSRF Protection**: All forms protected against CSRF attacks
- **🔐 SQL Injection Prevention**: Eloquent ORM with parameter binding
- **⚡ Rate Limiting**: API and form submission rate limiting
- **🔑 Secure Authentication**: Laravel Sanctum for API authentication
- **📊 Audit Logging**: Complete audit trail for admin actions
- **🔒 Permission System**: Granular role-based access control

## 🚀 Performance Features

- **⚡ Redis Caching**: Fast data retrieval with Redis
- **📦 Queue Processing**: Background job processing
- **🗜️ Asset Optimization**: Compressed CSS/JS assets
- **📱 Progressive Web App**: PWA features for mobile users
- **🔄 Database Optimization**: Optimized queries and indexing

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Coding Standards
- Follow PSR-12 coding standards
- Write comprehensive tests for new features
- Update documentation for any API changes
- Follow Laravel best practices

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 📞 Support

- **📧 Email**: support@smartparking.com
- **📚 Documentation**: [docs.smartparking.com](https://docs.smartparking.com)
- **🐛 Bug Reports**: [GitHub Issues](https://github.com/your-username/smart-parking-system/issues)
- **💬 Community**: [Discord Server](https://discord.gg/smartparking)

## 🙏 Acknowledgments

- Laravel Framework for the solid foundation
- Tailwind CSS for the beautiful UI components
- SSLCommerz for secure payment processing
- Redis for high-performance caching
- All contributors who helped build this project

---

**Built with ❤️ for the Smart Parking Community**
