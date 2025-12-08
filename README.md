# WatchTheFlix

A full-featured Laravel 12 streaming platform inspired by Stremio, with Real-Debrid integration, TMDB API, TV Guide, Platform Availability, and a sleek dark theme.

> **📋 Documentation Accuracy**: This README clearly distinguishes between **implemented features** (✅ working now) and **planned features** (📋 roadmap). See the [Current Limitations](#current-limitations) and [Roadmap](#roadmap) sections for complete transparency.

> **📊 Repository Audit**: For a comprehensive analysis of implementation status, see [AUDIT.md](AUDIT.md), [GAP_ANALYSIS.md](GAP_ANALYSIS.md), and [ROADMAP.md](ROADMAP.md).

## Quick Start

**What you get out of the box:**
- 🎬 Media catalog with watchlists, favorites, ratings, comments, and reactions
- 📺 TV Guide for UK and US channels (manual data seeding)
- 🌐 Platform availability tracking (Netflix, Prime, Hulu, etc.)
- 💬 Custom forum system with categories, threads, and subscriptions
- 👤 User profiles with 2FA and parental controls (PIN-protected)
- 🔐 Invite-only registration system
- 👨‍💼 Comprehensive admin panel with advanced analytics
- 🎨 Dark theme with responsive design
- 📡 Xtream Codes API for IPTV applications
- 🎵 Playlist creation and management
- 📊 Advanced search with multiple filters
- 📧 Email notifications for important events
- 🔗 Social sharing to Twitter, Facebook, LinkedIn, WhatsApp

**Optional integrations:**
- 🎬 TMDB API for rich metadata and automated content scraping
- 🚀 Real-Debrid for premium content access (user-level setting)

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
- **Parental Controls**: PIN-protected content restrictions with 4-digit PIN
- **Two-Factor Authentication (2FA)**: Google Authenticator support with recovery codes
- **Session Management**: Secure authentication with remember me functionality
- **Email Notifications**: Receive notifications via email for forum replies and important events
- **In-App Notifications**: Real-time notification system with bell icon, unread indicators, and mark-as-read functionality

### 📡 Xtream Codes API (IPTV Support)
- **Full Xtream Codes Compatibility**: Backend API compatible with popular IPTV applications
- **Player API**: Complete player_api.php implementation with all standard actions
- **M3U Playlist Generation**: Auto-generated playlists for live TV and VOD content
- **EPG XML Export**: XMLTV format electronic program guide data
- **VOD Streaming API**: Movie and series streaming with category organization
- **Authentication Tokens**: Secure API access with Laravel Sanctum tokens
- **Compatible Players**: TiviMate, Perfect Player, GSE Smart IPTV, IPTV Smarters, Kodi, VLC
- **Stream URLs**: Direct live TV and VOD stream access via Xtream format URLs
- **Full Documentation**: See [XTREAM_API.md](XTREAM_API.md) for complete API reference

### 🎯 Real-Debrid Integration
- **User-Level Integration**: Each user can enable/disable Real-Debrid
- **API Token Management**: Secure storage and validation of Real-Debrid tokens
- **Content Access Control**: Restrict premium content to Real-Debrid users
- **Token Validation**: Automatic validation of Real-Debrid API tokens

### 📺 TV Guide
- **Multi-Country Support**: Browse TV channels from UK and US
- **Program Schedules**: View program schedules with detailed information
- **Channel Information**: Access channel numbers, descriptions, and logos
- **Search Functionality**: Find specific programs across all channels
- **Country Filtering**: Filter programs by country (UK/US)
- **Automated EPG Updates**: Scheduled XMLTV data fetching (daily at 3:00 AM)
  - Configure via `EPG_PROVIDER_URL` in `.env`
  - Manual updates: `php artisan epg:update`
  - See [EPG_SETUP.md](EPG_SETUP.md) for detailed configuration

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

## Production Readiness

WatchTheFlix is production-ready with the following infrastructure:

### Implemented Features ✅
- ✅ **In-App & Email Notifications**: Fully functional with bell icon and email support
- ✅ **Automated EPG Updates**: Scheduled XMLTV data fetching from external EPG providers (see [EPG_SETUP.md](EPG_SETUP.md))
- ✅ **Multi-Language UI**: Full internationalization with 5 languages (English, Spanish, French, German, Italian)
- ✅ **Subtitle Support**: Multi-language subtitle upload and management (SRT/VTT formats)
- ✅ **Two-Factor Authentication**: Google Authenticator integration with recovery codes
- ✅ **Advanced Search**: Genre, year range, rating, and platform-based filtering
- ✅ **Social Sharing**: Share content to Twitter, Facebook, LinkedIn, WhatsApp
- ✅ **Playlist Creation**: Full CRUD system for custom playlists
- ✅ **Advanced Analytics**: Comprehensive admin dashboard with user engagement metrics
- ✅ **TMDB Bulk Import**: Admin interface for bulk importing content from TMDB
- ✅ **Xtream Codes API**: Complete IPTV backend with admin UI

### Infrastructure Packages ✅
- ✅ **Laravel Sanctum**: API token authentication for Xtream Codes and external integrations
- ✅ **Laravel Scout**: Full-text search capabilities (database/Meilisearch/Algolia support)
- ✅ **Intervention Image**: Automatic image optimization and processing
- ✅ **Redis Support**: Production-ready caching, sessions, and queue management
- ✅ **Video.js Player**: Professional HTML5 video player with HLS/DASH streaming
- ✅ **Queue System**: Background job processing for emails and heavy tasks

### Mobile & Casting
- ✅ **Responsive Web Design**: Mobile-optimized web interface
- ✅ **Video.js Integration**: Ready for HLS adaptive streaming
- ❌ **Native Mobile Apps**: iOS and Android apps not yet available
- ❌ **Casting Support**: Chromecast/AirPlay integration not yet implemented

### Performance Optimization
- **Database**: SQLite by default, easily switchable to MySQL/PostgreSQL for production
- **Caching**: File cache for development, Redis for production (configured)
- **Queue**: Sync for development, Redis for production (configured)
- **Search**: Database driver for development, Meilisearch/Algolia for production (configured)
- **Sessions**: Database for development, Redis for production (configured)

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
- Using the `media:scrape` command to fetch latest content
- Using the `TmdbMediaSeeder` to bulk import popular content

**Note**: The application works without TMDB API - you can manually add media through the admin panel. TMDB integration is optional but recommended for rich metadata.

### Real-Debrid Setup (Optional)

Real-Debrid integration is **optional** - the platform works fully without it. Enable it only if you want premium content access.

1. Get your API token from [Real-Debrid](https://real-debrid.com/apitoken)
2. Log in to your WatchTheFlix account
3. Go to Settings
4. Enable Real-Debrid and paste your API token
5. Save settings

**Note**: Real-Debrid is a user-level setting. Each user decides whether to enable it for their account.

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
3. **TV Guide**: Browse UK and US TV channel schedules (populated via database seeders)
4. **Watch Content**: Stream movies and series
5. **Build Your Library**: Add content to your watchlist and favorites
6. **Engage**: Rate, comment, and react to content
7. **Forum Participation**: Join discussions, subscribe to threads, and engage with the community
8. **Real-Debrid**: Enable Real-Debrid in settings for premium content
9. **Platform Discovery**: See which streaming services offer specific content

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

### Implemented Security Features ✅
- **CSRF Protection**: All forms protected against Cross-Site Request Forgery attacks
- **SQL Injection Prevention**: Eloquent ORM with parameterized queries
- **XSS Protection**: Blade templating engine with automatic output escaping
- **Password Security**: bcrypt hashing with secure password requirements
- **API Token Encryption**: Secure storage of Real-Debrid and TMDB API tokens
- **Authentication Rate Limiting**: Brute force protection on login attempts
- **Session Management**: Secure session handling with configurable timeouts
- **Parental Controls**: PIN-protected content restrictions (4-digit PIN)
- **Role-Based Access**: Admin and user roles with permission management (Spatie)
- **Activity Logging**: Comprehensive audit trail of admin actions (Spatie Activity Log)

### Planned Security Enhancements 📋
- **Two-Factor Authentication (2FA)**: Additional authentication layer (see [Roadmap](#roadmap))
- **API Rate Limiting**: Granular rate limiting for API endpoints
- **Content Security Policy (CSP)**: Enhanced XSS protection headers
- **IP-Based Restrictions**: Configurable IP whitelisting for admin panel

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
- [x] **Forum System**: Custom-built forum with categories, threads, and replies
- [x] **Forum Subscriptions**: Subscribe to threads and get in-app notifications
- [x] **View Tracking**: Track thread views and engagement
- [x] **Parental Controls**: PIN-protected content restrictions

### High Priority Features ✅ COMPLETED
- [x] **Email Notifications**: Send email notifications for important events
- [x] **Two-Factor Authentication (2FA)**: Add an extra layer of account security
- [x] **Automated EPG Updates**: Real-time TV guide data integration from external sources
- [x] **Advanced Search**: Enhanced search with filters for genre, year, rating, platform
- [x] **Platform-Based Filtering**: Filter media by streaming service availability

### Medium Priority Features ✅ COMPLETED
- [x] **Subtitle Support**: Multi-language subtitle parsing and display in video player
- [x] **Multi-Language UI**: Internationalization support for the entire interface
- [x] **Social Sharing**: Share content to social media platforms
- [x] **Playlist Creation**: Create and manage custom playlists
- [x] **Advanced Analytics**: Comprehensive admin dashboard with user engagement metrics
- [x] **TMDB Bulk Import UI**: Admin interface for bulk importing content from TMDB
- [x] **Xtream Codes API**: Complete IPTV backend with full admin UI

### Future Enhancements 🚀
- [ ] **Watch Party**: Synchronized viewing with friends
- [ ] **Mobile Apps**: Native iOS and Android applications
- [ ] **Chromecast/AirPlay**: Cast content to TVs and streaming devices
- [ ] **Content Recommendations**: AI-powered personalized recommendations
- [ ] **User Reviews**: Full review system with helpful votes
- [ ] **Advanced Parental Controls**: Content rating-based automatic restrictions

---

**Built with ❤️ using Laravel 12**