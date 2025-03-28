# 🔥 FireUp PHP Framework

A modern, lightweight PHP framework designed for simplicity and ease of use. FireUp makes PHP development faster and more enjoyable for beginners while providing powerful features for experienced developers.

## 🌟 What Makes FireUp Special?

### 1. Instant API Mode
Unlike other frameworks that require complex API setup, FireUp provides instant API endpoints for your models. Just create a model and get RESTful APIs automatically:
```php
// Create a model
fireup create:model Product

// Instant API endpoints available:
// GET /api/products
// POST /api/products
// GET /api/products/{id}
// PUT /api/products/{id}
// DELETE /api/products/{id}
```

### 2. No ORM Lock-in
While Laravel forces Eloquent and CodeIgniter has limited options, FireUp gives you complete freedom:
- Use any database system
- Choose your own ORM
- Mix and match different database solutions
- Native PDO support with query builder

### 3. Built-in Plugin System
Unlike Laravel's package system or CodeIgniter's lack of plugins, FireUp offers:
- One-click plugin installation
- Plugin marketplace
- Automatic dependency management
- Hot-reload plugin support

### 4. Zero-Configuration Storage
Unlike WordPress requiring complex setup or Laravel needing multiple configs, FireUp provides:
- Automatic file organization
- Built-in CDN support
- Instant file upload handling
- No configuration needed

### 5. AI-Powered Development
Unique to FireUp:
- Code suggestions as you type
- Automatic code completion
- Smart error detection
- Performance optimization tips

### 6. Native WebSocket Support
Unlike other frameworks requiring additional packages:
- Built-in WebSocket server
- Real-time updates out of the box
- No additional setup needed
- Automatic client reconnection

### 7. Simple CLI Tools
Unlike complex Artisan commands or limited CodeIgniter CLI:
- Intuitive command names
- Built-in help system
- Interactive prompts
- No memorization needed

### 8. Flexible Theming
Unlike WordPress's rigid theme system or Laravel's lack of theming:
- Multiple active themes
- Theme inheritance
- Live theme switching
- Component-based theming

## 🔥 Why Choose FireUp Over Other Frameworks?

| Feature             | FireUp ✅ | Laravel ❌ | CodeIgniter ❌ | WordPress ❌ |  
|--------------------|----------|------------|---------------|-------------|  
| Simple Routing    | ✅ Instant | ❌ Requires Controllers | ❌ Requires Controllers | ❌ Hardcoded URLs |  
| Auto REST API    | ✅ Yes | ❌ Requires API Resource | ❌ Manual Setup | ❌ Plugins Required |  
| No ORM Lock-in   | ✅ Flexible | ❌ Forced Eloquent | ❌ Limited Query Builder | ❌ MySQL-Only |  
| Plugin System    | ✅ Yes | ❌ No (Uses Packages) | ❌ No | ✅ Yes |  
| No Setup Storage | ✅ Yes | ❌ Requires Config | ❌ No Built-in | ❌ Requires Plugins |  
| AI Assistant     | ✅ Yes | ❌ No | ❌ No | ❌ No |  
| WebSockets       | ✅ Built-in | ❌ Needs Laravel Echo | ❌ No | ❌ No |  
| Theming System   | ✅ Yes | ❌ No | ❌ No | ✅ Yes |  
| CLI Simplicity   | ✅ FireUp CLI | ❌ Artisan (Advanced) | ❌ Limited | ❌ WP-CLI (Complex) |  

## 🚀 Features

- **Simple & Lightweight MVC Structure**
- **Instant API Mode**
- **Ultra-Simple Database Handling**
- **Plugin System**
- **Performance Optimizations**
- **Built-in Security Features**
- **No-Setup File Storage**
- **AI-Powered Code Suggestions**
- **Native AJAX & WebSockets Support**
- **Simple CLI Tools**
- **Flexible Theming System**

## 📋 Requirements

- PHP 8.0 or higher
- PDO PHP Extension
- JSON PHP Extension
- Mbstring PHP Extension

## 🛠 Installation

### Via Composer

```bash
composer create-project fireup/fireup my-project
```

### Manual Installation

1. Clone the repository:
```bash
git clone https://github.com/yourusername/fireup.git
```

2. Install dependencies:
```bash
composer install
```

3. Copy the environment file:
```bash
cp .env.example .env
```

4. Generate application key:
```bash
fireup key:generate
```

## 🔧 XAMPP & WAMP Integration

### 1. Install XAMPP/WAMP
- Download [XAMPP](https://www.apachefriends.org/download.html) or [WAMP](https://www.wampserver.com/en/)
- Ensure Apache & MySQL are running

### 2. Install FireUp in XAMPP/WAMP
```bash
# Navigate to XAMPP htdocs
cd C:\xampp\htdocs
# Or WAMP www directory
cd C:\wamp64\www

# Create new FireUp project
composer create-project fireup/fireup myapp
```

### 3. Database Setup
1. Open phpMyAdmin (http://localhost/phpmyadmin)
2. Create new database (e.g., `fireup_db`)
3. Configure `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fireup_db
DB_USERNAME=root
DB_PASSWORD=
```
4. Run migrations:
```bash
fireup migrate
```

### 4. Running FireUp
#### Option 1: Using FireUp Server
```bash
cd C:\xampp\htdocs\myapp
fireup serve
```
Access at: http://localhost:8000

#### Option 2: Using XAMPP/WAMP Server
1. Configure `.htaccess` in `public/`:
```apache
RewriteEngine On
RewriteBase /myapp/
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?$1 [L,QSA]
```
2. Access at: http://localhost/myapp/public

### 5. Development Workflow
| Command | Description |
|---------|-------------|
| `fireup serve` | Start development server with visual setup UI |
| `fireup migrate` | Create database tables |
| `fireup golive` | Prepare for production |
| `fireup create:model` | Create database models |
| `fireup create:controller` | Create controllers |
| `fireup create:view` | Create views |

### 6. Database Management
- Use phpMyAdmin for visual database management
- Access at: http://localhost/phpmyadmin
- Create/modify tables visually
- Import/export data
- Manage users and permissions

## 🎯 Available Commands

### Server Commands
```bash
fireup serve              # Start the development server
fireup golive            # Prepare your project for production
```

### Model Commands
```bash
fireup create:model      # Create a new model
fireup make:model        # Alias for create:model
```

### Controller Commands
```bash
fireup create:controller # Create a new controller
fireup make:controller   # Alias for create:controller
```

### View Commands
```bash
fireup create:view       # Create a new view
fireup make:view         # Alias for create:view
```

### Database Commands
```bash
fireup migrate          # Run database migrations
fireup rollback         # Rollback the last migration
fireup migrate:fresh    # Drop all tables and re-run migrations
```

### Route Commands
```bash
fireup route:list       # List all registered routes
fireup route:clear      # Clear route cache
```

### Other Commands
```bash
fireup key:generate     # Generate application key
fireup config:cache     # Cache configuration
fireup config:clear     # Clear configuration cache
fireup view:cache       # Cache views
fireup view:clear       # Clear view cache
```

## 📚 Documentation

For detailed documentation, visit our [documentation website](https://fire-updev.vercel.app).

## 🔧 Command Options

### Model Creation
```bash
fireup create:model User --table=users --fillable=name,email --hidden=password
```

### Controller Creation
```bash
fireup create:controller UserController --resource --model=User
```

### View Creation
```bash
fireup create:view auth.login --layout=auth
```

### Server Options
```bash
fireup serve --host=0.0.0.0 --port=8080
```

## 🏗 Project Structure

```
my-project/
├── app/
│   ├── Controllers/
│   ├── Models/
│   └── Views/
├── config/
├── database/
│   └── migrations/
├── public/
│   ├── css/
│   ├── js/
│   └── images/
├── routes/
├── storage/
├── vendor/
├── .env
├── .gitignore
├── composer.json
└── index.php
```

## 🤝 Contributing

We welcome contributions! Please see our [contributing guide](CONTRIBUTING.md) for details.

## 📄 License

FireUp is open-sourced software licensed under the [MIT license](LICENSE).

## 📞 Support

- Documentation: [https://fire-updev.vercel.app](https://fire-updev.vercel.app)
- Issues: [https://github.com/fireup/fireup/issues](https://github.com/fireup/fireup/issues)
- Email: jethrojerrybj@gmail.com

## 🙏 Acknowledgments

- Inspired by Laravel, CodeIgniter, and WordPress
- Built with ❤️ by the FireUp Team