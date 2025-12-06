# WatchTheFlix

A full-featured Laravel 12 streaming platform inspired by Stremio, with Real-Debrid integration and a sleek dark theme.

![Homepage](https://github.com/user-attachments/assets/ab82a2c5-10af-4401-870b-a85458cd689a)
![Login Page](https://github.com/user-attachments/assets/8f7413b1-b4ce-4eab-90c3-07af07290ba1)

## Features

### 🎬 Core Streaming Features
- **Media Catalog**: Browse movies, TV series, and episodes
- **Watchlists**: Create and manage personal watchlists
- **Favorites**: Mark your favorite content
- **Ratings**: Rate content on a 1-10 scale
- **Comments**: Engage with threaded comments
- **Reactions**: Express your feelings with reaction emojis (like, love, laugh, sad, angry)
- **Viewing History**: Track your watching progress

### 🔐 Authentication & User Management
- **Invite-Only Registration**: Controlled user onboarding with one-time invite codes
- **First User Admin**: The first registered user automatically becomes an admin
- **Rich User Profiles**: Customizable profiles with avatar, bio, and statistics
- **Parental Controls**: PIN-protected content restrictions
- **Session Management**: Secure authentication with remember me functionality

### 🎯 Real-Debrid Integration
- **User-Level Integration**: Each user can enable/disable Real-Debrid
- **API Token Management**: Secure storage and validation of Real-Debrid tokens
- **Content Access Control**: Restrict premium content to Real-Debrid users
- **Token Validation**: Automatic validation of Real-Debrid API tokens

### 👨‍💼 Admin Panel
- **Dashboard**: Overview of users, media, and activity
- **Media Management**: Full CRUD operations for media content
- **User Management**: View and manage user accounts
- **Invite System**: Generate and manage invite codes
- **Global Settings**: Configure platform-wide settings
- **Activity Logging**: Track all important actions (powered by Spatie Activity Log)

### 🎨 Modern UI/UX
- **Dark Theme**: GitHub Copilot-inspired dark color scheme
- **Responsive Design**: Mobile-first approach with TailwindCSS
- **Component-Based**: Reusable UI components
- **Minimal Cookie Consent**: One-time banner with minimal tracking
- **Clean Navigation**: Intuitive menu structure

### 📦 Spatie Package Integration
- **laravel-permission**: Role and permission management
- **laravel-activitylog**: Comprehensive activity logging
- **laravel-sluggable**: SEO-friendly URLs
- **laravel-backup**: Database and file backups
- **laravel-sitemap**: Automatic sitemap generation
- **laravel-tags**: Flexible tagging system

## Technology Stack

- **Framework**: Laravel 12
- **Frontend**: TailwindCSS 3.4 + Vite
- **Database**: SQLite (easily switchable to MySQL/PostgreSQL)
- **Authentication**: Laravel Breeze-style authentication
- **Asset Building**: Vite with HMR support
- **PHP Version**: 8.2+
- **Node.js Version**: 18+

## Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js 18+ and npm
- SQLite extension enabled

### Steps

1. **Clone the repository**
```bash
git clone https://github.com/Git-Cosmo/WatchTheFlix.git
cd WatchTheFlix
```

2. **Install PHP dependencies**
```bash
composer install
```

3. **Install Node dependencies**
```bash
npm install
```

4. **Setup environment**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Configure database**
The application uses SQLite by default. The database file will be created automatically.

For MySQL/PostgreSQL, update your `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=watchtheflix
DB_USERNAME=root
DB_PASSWORD=
```

6. **Run migrations**
```bash
php artisan migrate
```

7. **Seed the database** (optional)
```bash
php artisan db:seed
```

This creates:
- Admin user: `admin@watchtheflix.com` / `password`
- Sample media content

8. **Build frontend assets**
```bash
npm run build
```

For development with hot reload:
```bash
npm run dev
```

9. **Start the application**
```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## Configuration

### Real-Debrid Setup

1. Get your API token from [Real-Debrid](https://real-debrid.com/apitoken)
2. Log in to your WatchTheFlix account
3. Go to Settings
4. Enable Real-Debrid and paste your API token
5. Save settings

### Admin Features

The first registered user automatically becomes an admin with access to:
- `/admin/dashboard` - Admin dashboard
- `/admin/media` - Media management
- `/admin/users` - User management
- `/admin/invites` - Invite code generation
- `/admin/settings` - Global settings

### Invite System

1. Admin generates invite codes with optional expiration
2. Each invite is tied to a specific email address
3. Invite codes are one-time use only
4. Registration URL: `http://yoursite.com/register?invite=XXXX-XXXX`

## Usage

### For Users

1. **Registration**: Use an invite code to sign up (first user doesn't need one)
2. **Browse Content**: Explore the media catalog
3. **Watch Content**: Stream movies and series
4. **Build Your Library**: Add content to your watchlist and favorites
5. **Engage**: Rate, comment, and react to content
6. **Real-Debrid**: Enable Real-Debrid in settings for premium content

### For Admins

1. **Add Media**: Create new media entries with metadata
2. **Manage Users**: View user statistics and manage accounts
3. **Generate Invites**: Create invite codes for new users
4. **Monitor Activity**: View activity logs and statistics
5. **Configure Settings**: Adjust platform-wide settings

## Development

### Running Tests
```bash
php artisan test
```

### Code Style
```bash
./vendor/bin/pint
```

### Building for Production
```bash
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Database Schema

### Key Tables
- `users` - User accounts with Real-Debrid settings
- `media` - Movies, series, and episodes
- `watchlists` - User watchlist entries
- `favorites` - User favorites
- `ratings` - User ratings (1-10)
- `comments` - Threaded comments
- `reactions` - User reactions (like, love, laugh, sad, angry)
- `invites` - Invite codes for registration
- `viewing_history` - Watch progress tracking
- `settings` - Platform configuration

## Security

- CSRF protection on all forms
- SQL injection prevention via Eloquent ORM
- XSS protection via Blade templating
- Password hashing with bcrypt
- API token encryption
- Rate limiting on authentication
- Secure session management

## API Documentation

### Real-Debrid Integration

The application integrates with Real-Debrid's REST API v1.0:
- Token validation
- User information retrieval
- Link unrestricting
- Torrent management

## Troubleshooting

### Common Issues

**Database not found**
- Ensure `database/database.sqlite` exists
- Run `touch database/database.sqlite`
- Run migrations again

**Assets not loading**
- Run `npm run build`
- Clear browser cache
- Check `public/build` directory exists

**Permission denied**
- Ensure `storage` and `bootstrap/cache` are writable
```bash
chmod -R 755 storage bootstrap/cache
```

**Real-Debrid token invalid**
- Verify token at real-debrid.com/apitoken
- Check for extra spaces when pasting
- Ensure account is active

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Run tests and linting
5. Submit a pull request

## License

This project is open-source software licensed under the MIT license.

## Credits

- **Laravel Framework**: Taylor Otwell and contributors
- **TailwindCSS**: Adam Wathan and contributors
- **Spatie Packages**: Spatie team
- **Dark Theme**: Inspired by GitHub Copilot's interface

## Support

For issues and questions:
- Open an issue on GitHub
- Check existing documentation
- Review Laravel documentation

## Roadmap

### Planned Features
- [ ] Advanced search with filters
- [ ] Subtitle support
- [ ] Multi-language support
- [ ] Email notifications
- [ ] Two-factor authentication
- [ ] Social sharing
- [ ] Playlist creation
- [ ] Watch party feature
- [ ] Mobile apps (iOS/Android)
- [ ] Chromecast/AirPlay support
- [ ] TMDB API integration for metadata
- [ ] Automated content imports
- [ ] Advanced analytics dashboard

---

**Built with ❤️ using Laravel 12**