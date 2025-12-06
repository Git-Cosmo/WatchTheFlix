# WatchTheFlix

A full-featured Laravel 12 streaming platform inspired by Stremio, with Real-Debrid integration, TMDB API, TV Guide, Platform Availability, and a sleek dark theme.

![Homepage](https://github.com/user-attachments/assets/473a1e94-570d-4807-8743-1a3e142dfe22)
![TV Guide](https://github.com/user-attachments/assets/055322be-0960-4a3e-8774-e7c09d2f3e70)
![UK TV Channels](https://github.com/user-attachments/assets/2afd8ce4-01af-4e62-8145-d3c7a4081bb4)
![Platform Availability](https://github.com/user-attachments/assets/149096b6-e378-4269-ae7e-000ff8f5614c)
![Admin Settings - TMDB API](https://github.com/user-attachments/assets/76b26470-eee9-4132-b600-47b2cbf3960e)

## Features

### 🎬 Core Streaming Features
- **Media Catalog**: Browse movies, TV series, and episodes
- **Platform Availability**: See which streaming services (Netflix, Prime, Hulu, etc.) offer each title
- **TV Guide**: Browse UK and US TV channels with program schedules
- **Watchlists**: Create and manage personal watchlists
- **Favorites**: Mark your favorite content
- **Ratings**: Rate content on a 1-10 scale
- **Comments**: Engage with threaded comments
- **Reactions**: Express your feelings with reaction emojis (like, love, laugh, sad, angry)
- **Viewing History**: Track your watching progress
- **TMDB Integration**: Import rich metadata from The Movie Database API

### 🔐 Authentication & User Management
- **Invite-Only Registration**: Controlled user onboarding with one-time invite codes
- **First User Admin**: The first registered user automatically becomes an admin
- **Rich User Profiles**: Customizable profiles with avatar, bio, and statistics
- **Parental Controls**: PIN-protected content restrictions
- **Session Management**: Secure authentication with remember me functionality
- **User Notifications**: In-app notification system with bell icon, unread indicators, and mark-as-read functionality

### 🎯 Real-Debrid Integration
- **User-Level Integration**: Each user can enable/disable Real-Debrid
- **API Token Management**: Secure storage and validation of Real-Debrid tokens
- **Content Access Control**: Restrict premium content to Real-Debrid users
- **Token Validation**: Automatic validation of Real-Debrid API tokens

### 📺 TV Guide
- **Multi-Country Support**: Browse TV channels from UK and US
- **Live Programs**: See what's currently airing on each channel
- **Program Schedules**: View upcoming programs with detailed information
- **Channel Information**: Access channel numbers, descriptions, and logos
- **Search Functionality**: Find specific programs across all channels
- **Country Filtering**: Filter programs by country (UK/US)

### 🎯 Platform Availability
- **Streaming Services**: Track where content is available (Netflix, Prime, Hulu, etc.)
- **UK Services**: BBC iPlayer, ITV Hub, Channel 4, Sky Go, Now TV, BritBox
- **US Services**: Netflix, Amazon Prime, Hulu, Disney+, HBO Max, Apple TV+
- **Platform Links**: Direct links to content on each streaming platform
- **Subscription Info**: See which platforms require subscriptions
- **Easy Assignment**: Admins can easily assign platforms to media content

### 🎬 TMDB API Integration
- **Rich Metadata**: Import movie and TV show details from The Movie Database
- **Automated Imports**: Fetch posters, backdrops, descriptions, and ratings
- **Cast & Crew**: Access comprehensive cast and crew information
- **Watch Providers**: Automatically detect streaming platform availability
- **Admin Configuration**: Easily configure API key through admin settings
- **Documented Service**: Well-documented service class for TMDB API calls
- **Media Scraper**: Automated scraper command to fetch and update latest content from TMDB
- **Bulk Seeding**: Seed database with top 50 movies and TV shows instantly

### 💬 Community Forum
- **Forum Categories**: Organized discussion sections
- **Thread Creation**: Start new discussions
- **Reply System**: Engage in threaded conversations
- **Pin & Lock**: Admin moderation tools for important threads
- **Subscriptions**: Get notified of replies to threads you follow
- **View Tracking**: See how popular each thread is
- **Admin Management**: Full CRUD for forum categories

### 👨‍💼 Admin Panel
- **Quick Access**: Admin panel link available in user dropdown menu for authorized users
- **Dashboard**: Overview of users, media, and activity
- **Media Management**: Full CRUD operations for media content with platform assignment
- **User Management**: View and manage user accounts
- **Invite System**: Generate and manage invite codes
- **Forum Management**: Create and organize forum categories
- **Global Settings**: Configure platform-wide settings including TMDB API
- **Activity Logging**: Track all important actions (powered by Spatie Activity Log)

### 🎨 Modern UI/UX
- **Dark Theme**: GitHub Copilot-inspired dark color scheme
- **Responsive Design**: Mobile-first approach with TailwindCSS
- **Component-Based**: Reusable UI components
- **Minimal Cookie Consent**: One-time banner with minimal tracking
- **Clean Navigation**: Intuitive menu structure with notification bell
- **Enhanced Home Page**: Beautiful hero section with gradient effects, decorative elements, and feature showcase
- **Fixed Footer**: Properly positioned footer that stays at the bottom of the page

### 📦 Spatie Package Integration
- **laravel-permission**: Role and permission management
- **laravel-activitylog**: Comprehensive activity logging
- **laravel-sluggable**: SEO-friendly URLs
- **laravel-backup**: Database and file backups
- **laravel-sitemap**: Automatic sitemap generation
- **laravel-tags**: Flexible tagging system

**Note on Forum**: The project uses a custom-built forum system designed specifically for Laravel 12 compatibility. The `riari/laravel-forum` package was not used as it only supports Laravel 11.

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
- Forum categories

To seed additional data:
```bash
# Seed streaming platforms (Netflix, Prime, Hulu, etc.)
php artisan db:seed --class=PlatformSeeder

# Seed TV channels (UK and US)
php artisan db:seed --class=TvChannelSeeder

# Seed TV program guide (7 days of sample EPG data)
php artisan db:seed --class=TvProgramSeeder

# Seed top 50 movies and TV shows from TMDB (requires API key)
php artisan db:seed --class=TmdbMediaSeeder
```

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

### TMDB API Setup

1. Get your API key from [TMDB](https://www.themoviedb.org/settings/api)
2. Log in as an admin
3. Go to `/admin/settings`
4. Enter your TMDB API key in the "API Integrations" section
5. Save settings

The TMDB API enables:
- Importing rich metadata for movies and TV shows
- Automatic poster and backdrop downloads
- Cast and crew information
- Streaming platform availability detection

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
2. **Browse Content**: Explore the media catalog with platform availability info
3. **TV Guide**: Check what's airing now on UK and US TV channels
4. **Watch Content**: Stream movies and series
5. **Build Your Library**: Add content to your watchlist and favorites
6. **Engage**: Rate, comment, and react to content
7. **Real-Debrid**: Enable Real-Debrid in settings for premium content
8. **Platform Discovery**: See which streaming services offer specific content

### For Admins

1. **Add Media**: Create new media entries with metadata and assign platforms
2. **Configure TMDB**: Set up TMDB API for automatic metadata imports
3. **Manage Users**: View user statistics and manage accounts
4. **Generate Invites**: Create invite codes for new users
5. **Monitor Activity**: View activity logs and statistics
6. **Configure Settings**: Adjust platform-wide settings including API integrations
7. **Scrape Latest Content**: Use the media scraper to automatically fetch and update content

### Media Scraping

WatchTheFlix includes an automated media scraper that fetches the latest movies and TV shows from TMDB:

```bash
# Scrape both movies and TV shows (20 of each by default)
php artisan media:scrape

# Scrape only movies
php artisan media:scrape --type=movies --limit=50

# Scrape only TV shows
php artisan media:scrape --type=tv --limit=30
```

The scraper will:
- Add new content to your database
- Update existing content with fresh data
- Skip duplicates automatically
- Provide detailed statistics on completion

**Note**: Requires TMDB API key to be configured in Admin Settings.

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
- `platforms` - Streaming platforms (Netflix, Prime, Hulu, etc.)
- `media_platform` - Many-to-many relationship between media and platforms
- `tv_channels` - UK and US TV channels
- `tv_programs` - TV program schedules (EPG data)
- `watchlists` - User watchlist entries
- `favorites` - User favorites
- `ratings` - User ratings (1-10)
- `comments` - Threaded comments
- `reactions` - User reactions (like, love, laugh, sad, angry)
- `invites` - Invite codes for registration
- `viewing_history` - Watch progress tracking
- `settings` - Platform configuration (includes TMDB API key)
- `notifications` - User notifications (welcome messages, updates, etc.)
- `forum_categories` - Forum discussion categories
- `forum_threads` - Forum discussion threads
- `forum_posts` - Thread replies
- `forum_thread_subscriptions` - Thread subscription tracking

## Security

- CSRF protection on all forms
- SQL injection prevention via Eloquent ORM
- XSS protection via Blade templating
- Password hashing with bcrypt
- API token encryption
- Rate limiting on authentication
- Secure session management

## API Documentation

### TMDB API Integration

The application integrates with The Movie Database (TMDB) API v3:
- Search for movies and TV shows
- Retrieve detailed metadata (posters, backdrops, descriptions)
- Access cast and crew information
- Detect streaming platform availability
- Import ratings and release information

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

**TMDB API not working**
- Verify API key at themoviedb.org/settings/api
- Check API key is entered correctly in Admin Settings
- Ensure no extra spaces or characters in the API key

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

### Recently Completed ✅
- [x] **TV Guide**: Browse UK and US TV channels with program schedules
- [x] **Platform Availability**: See which streaming services offer each title
- [x] **TMDB API Integration**: Import rich metadata from The Movie Database
- [x] **Platform Management**: Assign and display streaming platforms for content

### Planned Features
- [ ] Advanced search with filters
- [ ] Platform-based filtering in media browse
- [ ] TMDB metadata import interface in admin panel
- [ ] Automated TV guide updates (EPG data integration)
- [ ] Subtitle support
- [ ] Multi-language support
- [ ] Email notifications
- [ ] Two-factor authentication
- [ ] Social sharing
- [ ] Playlist creation
- [ ] Watch party feature
- [ ] Mobile apps (iOS/Android)
- [ ] Chromecast/AirPlay support
- [ ] Advanced analytics dashboard

---

**Built with ❤️ using Laravel 12**