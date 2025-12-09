# WatchTheFlix

A full-featured Laravel 12 streaming platform inspired by Stremio, with Real-Debrid integration, TMDB API, TV Guide, Platform Availability, and a sleek Netflix-style dark theme.

> **📋 Documentation Accuracy**: This README clearly distinguishes between **implemented features** (✅ working now) and **planned features** (📋 roadmap). See the [Current Limitations](#current-limitations) and [Roadmap](#roadmap) sections for complete transparency.

> **📊 Repository Audit**: For a comprehensive analysis of implementation status, see [AUDIT.md](AUDIT.md), [GAP_ANALYSIS.md](GAP_ANALYSIS.md), and [ROADMAP.md](ROADMAP.md).

> **✅ Backend-Frontend Verification**: All backend features have corresponding frontend implementations. See [BACKEND_FRONTEND_VERIFICATION.md](BACKEND_FRONTEND_VERIFICATION.md) for detailed verification.

## Quick Start

**What you get out of the box:**
- 🎬 **TMDB-Powered Media Catalog**: Browse movies and TV shows with rich metadata, posters, and backdrops
  - Dedicated **Movies** and **TV Shows** pages with separate navigation
  - **Guest access** enabled - browse content without signing up
  - Netflix-style hero section with featured content backdrop
  - Latest Movies section with newest additions
  - Latest TV Shows section with recent series
  - Trending content section with popular titles
- 📺 **Enhanced TV Guide**: UK and US channels with live "On Air" indicators
  - Real-time program progress bars
  - Beautiful gradient designs and modern UI
  - Admin management with EPG sync
  - **Public access** - no login required
- 🌐 Platform availability tracking (Netflix, Prime, Hulu, etc.)
- 💬 Custom forum system with categories, threads, and subscriptions (auth required)
- 👤 User profiles with avatars (displayed in navbar), 2FA and parental controls (PIN-protected)
- 🔐 Invite-only registration system
- ⚙️ **Redesigned Settings Page**: Full-width modern layout with icon badges
  - Real-Debrid Integration with visual indicators
  - Parental Controls with PIN protection
  - Two-Factor Authentication setup
  - Password management
- 👨‍💼 **Polished Admin Panel**: Modern left-sidebar navigation with clean iconography
  - Dashboard with quick stats and growth metrics
  - Media management with bulk TMDB import
  - TV channel management with EPG sync
  - User and invite management
  - Forum and analytics tools
- 🎨 Netflix/Disney+ inspired dark theme with responsive design
- 🎵 Playlist creation and management
- 📊 Advanced search with multiple filters
- 📧 Email notifications for important events
- 🔗 Social sharing to Twitter, Facebook, LinkedIn, WhatsApp

**Optional integrations:**
- 🎬 TMDB API for rich metadata and automated content scraping (recommended)
- 🚀 Real-Debrid for premium content access (user-level setting)

> ⚠️ **FEATURE ON HOLD**: Xtream Codes API features are currently postponed until a future release (no ETA). The code remains available for reference, but is not actively maintained or recommended for production use at this time. WatchTheFlix is now focused on delivering a superior TMDB-based content catalog and enhanced TV Guide experience. See [Future Plans](#future-plans) for more details.

### Screenshots

**Homepage with Netflix-Style Hero Section**
![Homepage Guest View](https://github.com/user-attachments/assets/eff3beb7-5d35-40df-91af-f5a5ed0c7919)

**TV Guide with Live "On Air" Indicator**
![TV Guide Channel](https://github.com/user-attachments/assets/1c5cd335-c4d0-449f-a7fc-8ce4dbf47dfe)

**Previous Screenshots**
![TV Guide](https://github.com/user-attachments/assets/055322be-0960-4a3e-8774-e7c09d2f3e70)
![UK TV Channels](https://github.com/user-attachments/assets/2afd8ce4-01af-4e62-8145-d3c7a4081bb4)
![Platform Availability](https://github.com/user-attachments/assets/149096b6-e378-4269-ae7e-000ff8f5614c)
![Admin Settings - TMDB API](https://github.com/user-attachments/assets/76b26470-eee9-4132-b600-47b2cbf3960e)

## Features

## Recent Updates (December 2025)

### 🔍 Major Search & Discovery Enhancements
- **Site-Wide Search**: Comprehensive Laravel Scout-powered search across all content types
- **Smart Search Results**: Categorized results for movies, TV shows, channels, programs, and forum threads
- **Enhanced Media Pages**: TMDB-style layouts with trailers, directors, keywords, and comprehensive metadata
- **Rich TV Guide**: Live statistics, enhanced program cards, and better visual design

### 🎨 Major UI Overhaul
- **Netflix-Inspired Homepage**: Immersive hero section with featured content backdrop, large titles, and action buttons
- **Separate Navigation**: Distinct "Movies" and "TV Shows" menu items replacing generic "Browse"
- **Guest Access**: Browse movies, TV shows, and TV Guide without authentication
- **Enhanced TV Guide**: Live "On Air" indicators with real-time progress bars and modern gradient designs
- **Redesigned Settings**: Full-width layout with icon badges and better organization
- **Polished Admin UI**: Clean navigation with proper iconography and styling

### 🐛 Bug Fixes
- Fixed TV Guide channel page error (TvProgram rating field casting issue)
- Fixed SQLite compatibility in featured content queries
- Resolved Blade syntax errors in navigation

### 🎉 Latest Updates - TMDB Import & Search Improvements

#### TMDB Data Import & Display Enhancements
- **Complete TMDB Data Import**: All available TMDB fields are now imported and stored:
  - Basic info: title, original title, description, release date, runtime, genres
  - Media assets: poster, backdrop, trailer (YouTube), logos (up to 5), additional posters/backdrops (up to 10 each)
  - Cast & crew: Up to 20 cast members with profile photos and character names, crew with roles
  - Production details: Companies with logos, countries, spoken languages
  - Financial data: Budget, revenue, box office performance
  - Ratings: TMDB vote average and count, popularity score
  - Social links: IMDb ID, Facebook, Instagram, Twitter profiles
  - Additional metadata: Status, tagline, original language
  - TV-specific: Number of seasons, episodes, first/last air dates
  - Keywords: Auto-synced as searchable tags for better discovery

- **Rich Media Display Pages**: All imported data is now fully displayed on show pages:
  - Dual rating display (TMDB + IMDb if available)
  - Cast grid with profile photos and character names
  - Production company logos
  - Budget/revenue statistics
  - Social media links with branded icons
  - External links section (IMDb, Facebook, Instagram, Twitter)
  - Keywords/tags section for discovery
  - Director highlights with dedicated section
  - Enhanced metadata grid (language, status, seasons/episodes for TV)

#### Platform Availability Features
- **Streaming Platform Info**: Full integration with TMDB watch providers
  - Automatically attaches platforms during seeding (Netflix, Prime, Hulu, Disney+, HBO Max, etc.)
  - Display section on media pages showing where content is available
  - Platform logos and subscription requirements clearly indicated
  - Direct links to content on each streaming service
  - Supports 9 major streaming platforms with easy expansion

#### Modern Trending Carousel
- **Completely Redesigned Trending Section**:
  - Larger, more prominent cards (36-52 units wide vs 28-32)
  - Animated gradient title with pulsing fire emoji
  - Numbered ranking badges (1-N) with rotating animation on hover
  - Navigation arrows for smooth carousel scrolling
  - Smooth scroll behavior with snap points
  - Enhanced hover effects:
    - 110% scale on hover with smooth transitions
    - Dramatic shadow effects (accent-500/40)
    - Gradient overlays intensify on interaction
    - "Watch" button appears with play icon
    - Shine/shimmer effect animation
  - Rank badges with gradient (orange→red→pink) and rotation effect
  - Genre display alongside year
  - Better rating badges with backdrop blur and border
  - Comprehensive metadata display (title, year, genre, rating)
  - Scroll controls for easy navigation

#### Search Error Fix
- **Fixed Scout Database Driver Compatibility**: 
  - Removed `tags` field from `toSearchableArray()` method which was causing SQL errors
  - Error was: `SQLSTATE[42S22]: Column not found: 1054 Unknown column 'media.tags'`
  - Tags are stored in separate table via Spatie Tags package, not as column
  - Converted array fields (genres, cast, crew) to strings for proper database searching
  - Search now works flawlessly across title, description, type, genres, year, cast, and crew
  - Maintains full search functionality with proper Scout database driver support

#### Documentation Updates
- Complete TMDB field documentation in README
- Platform availability feature description
- Trending carousel showcase
- Search troubleshooting guide
- Seeding instructions with TMDB integration details

## Features

### 🔍 Search & Discovery
- **Site-Wide Search**: Laravel Scout-powered search across all content
  - Search movies, TV shows, channels, programs, forum threads, and users
  - Categorized results with type filters
  - Fast, indexed search for instant results
  - Smart relevance ranking
- **Advanced Filtering**: Genre, year, rating, and platform-based filters
- **Quick Search**: Navbar search with instant access from any page

### 🎬 Core Streaming Features
- **Media Catalog**: Browse movies, TV series, and episodes with rich metadata
  - **Dedicated Pages**: Separate /movies and /tv-shows routes with filtered content
  - **Public Access**: Guests can browse all content without signing up
  - **Enhanced Details**: TMDB-style layout with overview, cast, crew, trailers, and keywords
  - **Director Highlights**: Key crew members prominently displayed
  - **Keyword Tags**: Searchable tags imported from TMDB for better discovery
  - **Trailer Integration**: Embedded YouTube trailers on media pages
  - **Multiple Images**: Access to posters, backdrops, and logos
  - **Comprehensive Metadata**: Status, tagline, budget, revenue, production details
- **Platform Availability**: See which streaming services (Netflix, Prime, Hulu, etc.) offer each title
- **TV Guide**: Browse UK and US TV channels with program schedules
  - **Live Statistics**: Real-time counts of active channels and current programs
  - **Enhanced Program Cards**: Rich metadata display with genres, ratings, and descriptions
  - **"Live Now" Indicators**: Visual indicators for currently airing programs
  - **Smart Search**: Search across all TV programs with filters
- **Watchlists**: Create and manage personal watchlists
- **Favorites**: Mark your favorite content
- **Ratings**: Rate content on a 1-10 scale
- **Comments**: Engage with threaded comments
- **Reactions**: Express your feelings with reaction emojis (like, love, laugh, sad, angry)
- **Viewing History**: Track your watching progress
- **TMDB Integration**: Import rich metadata from The Movie Database API
- **SEO-Optimized URLs**: Automatic slug generation for all media with spatie/laravel-sluggable
- **Rich Media Details**: Cast photos, production companies, budgets, revenues, and social links
- **Genre Filtering**: Click genres to discover similar content

### 🔐 Authentication & User Management
- **Invite-Only Registration**: Controlled user onboarding with one-time invite codes
- **First User Admin**: The first registered user automatically becomes an admin
- **Rich User Profiles**: Customizable profiles with avatar, bio, and statistics
- **Parental Controls**: PIN-protected content restrictions with 4-digit PIN
- **Two-Factor Authentication (2FA)**: Google Authenticator support with recovery codes
- **Session Management**: Secure authentication with remember me functionality
- **Email Notifications**: Receive notifications via email for forum replies and important events
- **In-App Notifications**: Real-time notification system with bell icon, unread indicators, and mark-as-read functionality

### 📡 Xtream Codes API (⚠️ ON HOLD)

> **IMPORTANT**: Xtream Codes API features are currently postponed until a future release (no ETA). The implementation remains in the codebase for reference but is not actively maintained or recommended for production use. See [Future Plans](#future-plans) below.

<details>
<summary>Click to view postponed features</summary>

- **Full Xtream Codes Compatibility**: Backend API compatible with popular IPTV applications
- **Player API**: Complete player_api.php implementation with all standard actions
- **M3U Playlist Generation**: Auto-generated playlists for live TV and VOD content
- **EPG XML Export**: XMLTV format electronic program guide data
- **VOD Streaming API**: Movie and series streaming with category organization
- **Authentication Tokens**: Secure API access with Laravel Sanctum tokens
- **Compatible Players**: TiviMate, Perfect Player, GSE Smart IPTV, IPTV Smarters, Kodi, VLC
- **Stream URLs**: Direct live TV and VOD stream access via Xtream format URLs
- **Full Documentation**: See [XTREAM_API.md](XTREAM_API.md) for complete API reference (note: features are on hold)

</details>

### 🎯 Real-Debrid Integration
- **User-Level Integration**: Each user can enable/disable Real-Debrid
- **API Token Management**: Secure storage and validation of Real-Debrid tokens
- **Content Access Control**: Restrict premium content to Real-Debrid users
- **Token Validation**: Automatic validation of Real-Debrid API tokens

### 📺 TV Guide & Channel Management
- **Multi-Country Support**: Browse TV channels from UK and US
- **Program Schedules**: View program schedules with detailed information
- **Channel Information**: Access channel numbers, descriptions, and logos
- **Search Functionality**: Find specific programs across all channels
- **Country Filtering**: Filter programs by country (UK/US)
- **Admin Channel Management**: Full CRUD interface for TV channels
  - Add, edit, and remove channels
  - Bulk EPG sync from external providers
  - Channel statistics and program counts
  - Active/inactive status management
- **Automated EPG Updates**: Scheduled XMLTV data fetching (daily at 3:00 AM)
  - Configure via `EPG_PROVIDER_URL` in `.env`
  - Manual updates: `php artisan epg:update`
  - Admin UI sync button for on-demand updates
  - See [EPG_SETUP.md](EPG_SETUP.md) for detailed configuration

### 🎯 Platform Availability
- **Streaming Services**: Track where content is available (Netflix, Prime, Hulu, etc.)
- **UK Services**: BBC iPlayer, ITV Hub, Channel 4, Sky Go, Now TV, BritBox
- **US Services**: Netflix, Amazon Prime, Hulu, Disney+, HBO Max, Apple TV+
- **Platform Links**: Direct links to content on each streaming platform
- **Subscription Info**: See which platforms require subscriptions
- **Easy Assignment**: Admins can easily assign platforms to media content

### 🎬 Enhanced TMDB API Integration
- **Rich Metadata**: Import movie and TV show details from The Movie Database
- **Automated Imports**: Fetch posters, backdrops, descriptions, and ratings
- **Full Details Import**: 
  - Cast & Crew with profile photos (up to 20 each)
  - Production companies with logos
  - Budget, revenue, and box office data
  - Taglines and original titles
  - External IDs (IMDb, Facebook, Instagram, Twitter)
  - Multiple images (logos, posters, backdrops)
  - Keywords and spoken languages
- **Watch Providers**: Automatically detect streaming platform availability
- **Platform Assignment**: Auto-attach Netflix, Prime, Hulu, Disney+, etc. from TMDB data
- **Conditional Seeding**: `php artisan db:seed` automatically imports from TMDB if API key is configured
- **Admin Configuration**: Easily configure API key through admin settings
- **Documented Service**: Well-documented service class for TMDB API calls
- **Enhanced Media Scraper**: Automated scraper command with extended coverage
  - Scrape latest movies: `php artisan media:scrape --type=movies --limit=50`
  - Scrape latest TV shows: `php artisan media:scrape --type=tv --limit=50`
  - Scrape trending content: `php artisan media:scrape --type=trending --limit=50`
  - Scrape all: `php artisan media:scrape --type=all --limit=20`
- **Bulk Seeding**: Seed database with top 50 movies and TV shows instantly with full details
- **Home Page Sections**:
  - Latest Movies - newest movie additions
  - Latest TV Shows - recent TV series
  - Trending Now - popular content across movies and TV

### 💬 Community Forum
- **Forum Categories**: Organized discussion sections
- **Thread Creation**: Start new discussions
- **Reply System**: Engage in threaded conversations
- **Pin & Lock**: Admin moderation tools for important threads
- **Subscriptions**: Get notified of replies to threads you follow
- **View Tracking**: See how popular each thread is
- **Admin Management**: Full CRUD for forum categories

### 👨‍💼 Redesigned Admin Panel
- **Modern Left Sidebar Navigation**: Streamlined access to all admin features
- **Dashboard**: Overview of users, media, activity, and 30-day growth metrics
  - Quick stats cards with visual indicators
  - User engagement metrics (comments, ratings, favorites)
  - Growth tracking for users, media, and interactions
  - Quick action buttons for common tasks
- **Media Management**: Full CRUD operations for media content with platform assignment
- **TMDB Bulk Import**: Import movies and TV shows from TMDB with admin UI
- **TV Channel Management**: Full CRUD interface for TV channels
  - Add, edit, and remove channels
  - Bulk EPG sync functionality
  - Channel statistics and active/inactive status
- **User Management**: View and manage user accounts with detailed profiles
- **Invite System**: Generate and manage invite codes with expiration dates
- **Forum Management**: Create and organize forum categories
- **Analytics Dashboard**: Comprehensive analytics with user engagement insights
- **Activity Logging**: Track all important actions (powered by Spatie Activity Log)
- **Global Settings**: Configure platform-wide settings including TMDB API
- **Consistent Dark Theme**: Admin UI seamlessly matches main site design

### 🎨 Modern UI/UX
- **Dark Theme**: GitHub Copilot-inspired dark color scheme
- **Responsive Design**: Mobile-first approach with TailwindCSS
- **Component-Based**: Reusable UI components
- **Minimal Cookie Consent**: One-time banner with minimal tracking
- **Clean Navigation**: Intuitive menu structure with notification bell
- **Enhanced Home Page**: Beautiful hero section with gradient effects, decorative elements, and feature showcase
- **Fixed Footer**: Properly positioned footer that stays at the bottom of the page

### 🔍 SEO & Discoverability
- **SEO-Friendly URLs**: Automatic slug generation with spatie/laravel-sluggable
- **Rich Meta Tags**: OpenGraph and Twitter Card tags for social sharing
- **Structured Data**: Schema.org JSON-LD for movies and TV shows
- **Canonical URLs**: Proper canonical URL handling
- **Breadcrumb Navigation**: Structured breadcrumb data for search engines
- **Meta Descriptions**: Auto-generated from content descriptions
- **Sitemap Generation**: Automatic sitemap.xml generation with spatie/laravel-sitemap

### 🎨 User Experience
- **Attractive Error Pages**: Custom 403, 404, 419, 429, 500, and 503 error pages
- **Enhanced Media Pages**: 
  - Cast section with photos and character names
  - Production company logos
  - Budget and revenue information
  - External links (IMDb, Facebook, Instagram, Twitter)
  - Taglines and additional metadata
  - Genre tags that filter content
- **Social Sharing**: Share to Twitter, Facebook, LinkedIn, WhatsApp with one click
- **Copy Link**: Quick copy-to-clipboard functionality

### 📦 Spatie Package Integration
- **laravel-permission**: Role and permission management
- **laravel-activitylog**: Comprehensive activity logging
- **laravel-sluggable**: SEO-friendly URLs for all media
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
- ✅ **TMDB Bulk Import**: Admin interface for bulk importing content from TMDB with full details
- ✅ **SEO Optimization**: Sluggable URLs, rich meta tags, and structured data (Schema.org)
- ✅ **Enhanced Media Details**: Cast photos, production companies, budgets, social links
- ✅ **Custom Error Pages**: Branded 403, 404, 419, 429, 500, and 503 pages
- ✅ **Conditional TMDB Seeding**: Automatic TMDB import during database seeding if API key is set
- ✅ **Xtream Codes API**: Complete IPTV backend with admin UI (⚠️ ON HOLD)

### Infrastructure Packages ✅
- ✅ **Laravel Sanctum**: API token authentication for Xtream Codes and external integrations
- ✅ **Laravel Scout**: Full-text search capabilities (database/Meilisearch/Algolia support)
  - Enabled on Media, TvChannel, TvProgram, ForumThread, and User models
  - Database driver by default (no additional setup required)
  - Optional Meilisearch/Algolia for production performance
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

6. **Configure Optional Integrations** (Before seeding)

For automatic TMDB content import during seeding, add your TMDB API key to `.env`:
```env
TMDB_API_KEY=your_tmdb_api_key_here
```

For real EPG (TV Guide) data import during seeding, add your EPG provider URL:
```env
EPG_PROVIDER_URL=https://your-epg-provider.com/xmltv.xml
```

**Note**: Both are optional. Without these keys, the seeder will:
- Skip TMDB content import (no media seeded by default)
- Seed sample TV program data instead of real EPG data

7. **Run migrations and seed database**
```bash
php artisan migrate --seed
```

This creates:
- ✅ Admin user: `admin@watchtheflix.com` / `password` (⚠️ Change immediately!)
- ✅ Production-ready forum categories
- ✅ Streaming platforms (Netflix, Prime, Hulu, Disney+, etc.)
- ✅ TV channels (UK and US)
- ✅ **Real TMDB content** (Top 50 movies + TV shows) - *if TMDB_API_KEY is set*
- ✅ **Real EPG data** (Live TV schedules) - *if EPG_PROVIDER_URL is set*
- ✅ Sample TV program data (7 days) - *if EPG_PROVIDER_URL is NOT set*

**Important**: The seeder no longer creates sample movies/series. All media is imported from TMDB if the API key is configured.

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

WatchTheFlix supports flexible TMDB API key configuration with two methods:

#### Method 1: Environment Variable (.env) - Recommended for Initial Setup

Add your API key to `.env` file (this is the default source):
```env
TMDB_API_KEY=your_tmdb_api_key_here
```

**Benefits**:
- ✅ Automatic TMDB content import during `php artisan migrate --seed`
- ✅ Persists across deployments
- ✅ Easy to configure in production environments

#### Method 2: Admin Panel - Recommended for Runtime Changes

1. Get your API key from [TMDB](https://www.themoviedb.org/settings/api)
2. Log in as an admin
3. Go to `/admin/settings`
4. Enter your TMDB API key in the "API Integrations" section
5. Save settings

**Benefits**:
- ✅ Override .env value without redeploying
- ✅ Change API key at runtime without server restart
- ✅ Manage from the UI

**Priority**: Admin Panel setting takes precedence over .env. If admin panel value is empty/blank, it falls back to .env.

#### What TMDB API Enables

The TMDB API integration provides:
- 🎬 Importing rich metadata for movies and TV shows
- 🖼️ Automatic poster and backdrop downloads
- 👥 Cast and crew information with photos
- 🎭 Production companies and budget/revenue data
- 📺 Streaming platform availability detection
- 🔗 External links (IMDb, Facebook, Instagram, Twitter)
- 🏷️ Genres, keywords, and taglines
- ⭐ Ratings and popularity scores

**Commands**:
```bash
# Import top 50 movies and TV shows from TMDB
php artisan db:seed --class=TmdbMediaSeeder

# Sync existing media with latest TMDB data
php artisan tmdb:sync

# Scrape latest content from TMDB
php artisan media:scrape
```

**Note**: The application works fully without TMDB API - you can manually add media through the admin panel. TMDB integration is optional but highly recommended for rich metadata and automated content import.

### Enhanced TMDB Integration ✅ NEW

WatchTheFlix now includes comprehensive TMDB integration with extended metadata support:

#### Extended Metadata Fields
- **Production Details**: Companies, countries, spoken languages
- **Financial Data**: Budget and revenue information (for movies)
- **Additional Info**: Original titles, tagline, status, popularity scores
- **External IDs**: Facebook, Instagram, Twitter, IMDb links
- **Images**: Multiple posters, backdrops, and logos
- **TV Show Data**: Season/episode counts, air dates
- **Ratings**: Vote counts and averages from TMDB

#### Automatic Data Synchronization

Keep your content metadata fresh with automated TMDB syncing:

```bash
# Sync all media last updated more than 30 days ago (default)
php artisan tmdb:sync

# Sync all media regardless of last sync time
php artisan tmdb:sync --all

# Sync media updated more than 60 days ago
php artisan tmdb:sync --days=60
```

The sync command:
- ✅ Updates existing media with latest TMDB data
- ✅ Preserves your custom settings (stream URLs, Real-Debrid flags)
- ✅ Includes automatic rate limiting (TMDB allows 40 req/10s)
- ✅ Provides detailed progress tracking
- ✅ Logs all errors for troubleshooting

**Recommended**: Schedule this command to run weekly via Laravel's task scheduler:

```php
// In routes/console.php or app/Console/Kernel.php
Schedule::command('tmdb:sync --days=7')->weekly();
```

### EPG (Electronic Program Guide) Setup

WatchTheFlix includes robust EPG integration for real TV channel schedules and program data.

#### EPG Configuration

Add your EPG provider URL to `.env` file:
```env
EPG_PROVIDER_URL=https://your-epg-provider.com/xmltv.xml
```

**What happens with EPG_PROVIDER_URL**:
- ✅ **Set**: Real EPG data is fetched during `php artisan migrate --seed`
- ❌ **Not Set**: Sample TV program data (7 days) is seeded as fallback

#### Automated EPG Updates

EPG data is automatically updated **daily at 3:00 AM** via Laravel's task scheduler (configured in `routes/console.php`).

To enable automated updates:
```bash
# Add to server crontab (crontab -e)
* * * * * cd /path/to/WatchTheFlix && php artisan schedule:run >> /dev/null 2>&1
```

#### Manual EPG Updates

Update EPG data on-demand:
```bash
# Standard update (skips existing programs)
php artisan epg:update

# Force update (overwrites existing programs)
php artisan epg:update --force
```

#### EPG Data Format

WatchTheFlix supports standard **XMLTV format** EPG data. The EPG service:
- ✅ Parses XMLTV XML format
- ✅ Handles timezone offsets
- ✅ Matches programs to existing channels
- ✅ Prevents duplicate program entries
- ✅ Supports multi-channel imports
- ✅ Includes comprehensive error handling

#### Finding EPG Providers

**Free Options**:
- **XMLTV.org**: Community-maintained EPG data (varies by region)
- **Schedules Direct**: Free for personal use (US/Canada)

**Paid Options**:
- **EPG123**: Schedules Direct integration ($25/year)
- **IPTV-EPG**: Various providers (pricing varies)
- **Gracenote**: Enterprise-grade (contact for pricing)

#### EPG Features

- 📺 **Multi-Country Support**: UK and US channels pre-configured
- 🔄 **Automated Sync**: Daily updates keep schedules fresh
- 🎯 **Channel Matching**: Automatic program-to-channel mapping
- ⚡ **Bulk Import**: Efficient batch processing
- 📊 **Admin Interface**: Manage channels and view EPG statistics
- 🔍 **Search**: Find programs across all channels
- ⏰ **EPG Reminders**: Set reminders for upcoming programs

**For detailed EPG setup instructions, see [EPG_SETUP.md](EPG_SETUP.md)**

### SEO Features ✅ NEW

WatchTheFlix includes comprehensive SEO optimizations to improve search engine visibility:

#### Implemented SEO Features
- **SEO-Friendly URLs**: Automatic slug generation using spatie/laravel-sluggable
- **Unique Slugs**: Collision handling ensures all media has unique URLs
- **Meta Tags**: Dynamic meta descriptions and keywords for all pages
- **Canonical URLs**: Prevents duplicate content issues
- **Open Graph Tags**: Rich previews for Facebook, LinkedIn, and other platforms
- **Twitter Cards**: Beautiful card previews when sharing on Twitter
- **Schema.org Structured Data**: JSON-LD markup for movies and TV shows
- **Semantic HTML**: Proper heading hierarchy and semantic elements
- **Image Optimization**: Alt tags and optimized image loading

#### SEO Component Usage

The SEO meta component is automatically included on media pages. For custom pages:

```blade
@section('seo')
<x-seo-meta 
    :title="$pageTitle"
    :description="$description"
    :keywords="$keywords"
    :canonicalUrl="route('your.route')"
    :imageUrl="$image"
    :type="'website'"
/>
@endsection
```

#### SEO Best Practices
- All media entries generate unique SEO-friendly slugs automatically
- Meta descriptions are auto-generated from content descriptions (truncated to 160 chars)
- Keywords are extracted from genres and tags
- Social sharing images use poster URLs for rich previews
- Canonical URLs prevent duplicate content penalties

### Real-Debrid Setup (Optional)

Real-Debrid integration is **optional** - the platform works fully without it. Enable it only if you want premium content access.

1. Get your API token from [Real-Debrid](https://real-debrid.com/apitoken)
2. Log in to your WatchTheFlix account
3. Go to Settings
4. Enable Real-Debrid and paste your API token
5. Save settings

**Note**: Real-Debrid is a user-level setting. Each user decides whether to enable it for their account.

### Laravel Scout Configuration

WatchTheFlix uses Laravel Scout for site-wide search across media, channels, programs, forum threads, and users.

#### Default Configuration (Database Driver)

By default, Scout uses the `database` driver which requires **no additional setup**. This works well for:
- Development environments
- Small to medium deployments (< 10,000 records)
- Quick prototyping

Search is enabled on:
- **Media**: Movies and TV shows (title, description, genres, cast, crew, tags)
- **TV Channels**: Channel names and descriptions
- **TV Programs**: Program titles, descriptions, genres
- **Forum Threads**: Thread titles and bodies
- **Users**: Names, emails, bios (admin search only)

#### Production Configuration (Meilisearch/Algolia)

For production deployments with large datasets, use Meilisearch or Algolia for better performance:

**Meilisearch Setup**:
```bash
# Install Meilisearch (via Docker)
docker run -d -p 7700:7700 getmeili/meilisearch:latest

# Update .env
SCOUT_DRIVER=meilisearch
MEILISEARCH_HOST=http://127.0.0.1:7700
MEILISEARCH_KEY=your_master_key

# Index existing data
php artisan scout:import "App\Models\Media"
php artisan scout:import "App\Models\TvChannel"
php artisan scout:import "App\Models\TvProgram"
php artisan scout:import "App\Models\ForumThread"
```

**Algolia Setup**:
```bash
# Update .env
SCOUT_DRIVER=algolia
ALGOLIA_APP_ID=your_app_id
ALGOLIA_SECRET=your_admin_api_key

# Index existing data
php artisan scout:import "App\Models\Media"
php artisan scout:import "App\Models\TvChannel"
php artisan scout:import "App\Models\TvProgram"
php artisan scout:import "App\Models\ForumThread"
```

#### Search Features

- **Site-Wide Search**: Access via `/search?q=query`
- **Type Filtering**: Filter by media, channels, programs, forum threads
- **Relevance Ranking**: Results sorted by relevance automatically
- **Pagination**: Each result type paginated independently
- **Real-Time Updates**: New content automatically indexed
- **Fast Performance**: Sub-100ms search queries with Meilisearch/Algolia

#### Reindexing

When you need to rebuild search indexes:
```bash
# Flush all indexes
php artisan scout:flush "App\Models\Media"
php artisan scout:flush "App\Models\TvChannel"
php artisan scout:flush "App\Models\TvProgram"
php artisan scout:flush "App\Models\ForumThread"

# Re-import all data
php artisan scout:import "App\Models\Media"
php artisan scout:import "App\Models\TvChannel"
php artisan scout:import "App\Models\TvProgram"
php artisan scout:import "App\Models\ForumThread"
```

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
- `users` - User accounts with Real-Debrid settings and 2FA
- `media` - Movies, series, and episodes with extended TMDB metadata
  - ✅ **New**: SEO fields (meta_description, meta_keywords, og_tags, twitter_tags, canonical_url)
  - ✅ **New**: Extended TMDB fields (production companies/countries, budget, revenue, external IDs)
  - ✅ **New**: Additional images (multiple posters, backdrops, logos)
  - ✅ **New**: TV show fields (seasons, episodes, air dates)
  - ✅ **New**: Popularity and vote data
  - ✅ **New**: Last synced timestamp for TMDB updates
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
- `forum_categories` - Forum discussion categories with ordering and active status
- `forum_threads` - Forum discussion threads with pin/lock functionality
- `forum_posts` - Thread replies
- `forum_thread_subscriptions` - Thread subscription tracking
- `playlists` - User-created playlists with ordered media items

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

The application integrates comprehensively with The Movie Database (TMDB) API v3:

#### Core Features
- **Search**: Movies and TV shows by title
- **Details**: Full metadata including descriptions, release dates, runtime
- **Images**: Posters, backdrops, logos (multiple variants)
- **Cast & Crew**: Top 20 actors and key crew members
- **Videos**: Trailers from YouTube
- **Watch Providers**: Streaming platform availability
- **External IDs**: Links to IMDb, Facebook, Instagram, Twitter

#### Extended Metadata ✅ NEW
- **Production**: Companies, countries, spoken languages
- **Financial**: Budget and revenue data (movies only)
- **Status**: Released, In Production, Post Production, etc.
- **Popularity**: TMDB popularity scores and vote averages
- **TV Shows**: Season/episode counts, air dates, episode runtime
- **Keywords**: Content keywords for better categorization

#### API Endpoints Used
- `/search/movie` - Search movies
- `/search/tv` - Search TV shows
- `/movie/{id}?append_to_response=credits,videos,watch/providers,images,external_ids,keywords` - Full movie details
- `/tv/{id}?append_to_response=credits,videos,watch/providers,images,external_ids,keywords` - Full TV show details
- `/movie/popular` - Popular movies
- `/tv/popular` - Popular TV shows
- `/trending/all/week` - Trending content

#### Rate Limiting
- **TMDB Limit**: 40 requests per 10 seconds
- **Our Implementation**: 250ms delay between requests (4 req/sec) for sync command
- **Automatic**: Handles rate limiting transparently

#### Data Transformation
The `TmdbService` includes helper methods to transform raw TMDB data into our database format:
- `transformMovieData(array $tmdbData)` - Converts movie data with all extended fields
- `transformTvShowData(array $tmdbData)` - Converts TV show data with all extended fields
- Automatic SEO field generation from content metadata
- Preserves existing local settings (stream URLs, Real-Debrid flags)

### Real-Debrid Integration

The application integrates with Real-Debrid's REST API v1.0:
- Token validation
- User information retrieval
- Link unrestricting
- Torrent management

## Admin UI Improvements ✅ NEW

The admin panel has been redesigned with enhanced visual polish and improved user experience:

### UI Enhancements
- **Modern Button Styles**: Gradient backgrounds with smooth hover effects and scaling
- **Enhanced Cards**: Improved borders, hover states, and visual hierarchy
- **Sidebar Navigation**: Active state indicators with gradient backgrounds and smooth transitions
- **Table Redesign**: Media thumbnails, improved badges, and better data presentation
- **Form Improvements**: Better labels, placeholders, error states with icons
- **Responsive Design**: Optimized for mobile devices with improved spacing
- **Visual Feedback**: Icons, status indicators, and hover effects throughout
- **Color Consistency**: Unified color scheme using GitHub-inspired dark theme

### Key Improvements
- ✅ Media management table now shows thumbnails and improved metadata display
- ✅ Forum category management with visual badges and status indicators
- ✅ Enhanced form layouts with better visual hierarchy and user guidance
- ✅ Improved button interactions with gradients and hover animations
- ✅ Better empty states with helpful icons and call-to-action buttons
- ✅ Smooth transitions and animations throughout the interface

## Recent Bug Fixes ✅

### Fixed Route Naming Issues
**Issue**: `RouteNotFoundException` for `forum.admin.create` route  
**Cause**: Inconsistent route naming between route definitions and view files  
**Resolution**: Updated all admin forum routes to use consistent `admin.forum.admin.*` naming pattern

**Files Fixed**:
- `resources/views/admin/forum/index.blade.php`
- `resources/views/admin/forum/create.blade.php`
- `resources/views/admin/forum/edit.blade.php`
- `app/Http/Controllers/Admin/ForumManagementController.php`

### Fixed Undefined Variable Error
**Issue**: `Undefined variable $errors` in compiled Blade views  
**Cause**: Stale compiled view cache  
**Resolution**: Cleared compiled views and ensured proper error bag handling

The `$errors` variable is now properly available in all views through Laravel's validation middleware. To prevent this issue:
```bash
php artisan view:clear  # Clear compiled views
php artisan optimize:clear  # Clear all caches
```

### Database Migration Safety
**Issue**: TmdbService tried to access database during bootstrap before migrations ran  
**Cause**: Service constructor queried Settings table in service provider  
**Resolution**: Added try-catch error handling to gracefully handle missing database during migrations

## Troubleshooting

### Common Issues

**Route not found errors**
- Clear route cache: `php artisan route:clear`
- Verify route names match in views and controllers
- Check middleware groups are properly defined

**Compiled view errors**
- Clear view cache: `php artisan view:clear`
- Delete files in `storage/framework/views/`
- Rebuild views by visiting the pages

**Database not found**
- Ensure `database/database.sqlite` exists
- Run `touch database/database.sqlite`
- Run migrations again

**Assets not loading**
- Run `npm run build`
- Clear browser cache
- Check `public/build` directory exists

**TMDB Sync failures**
- Verify TMDB API key is set in Admin Settings
- Check rate limiting (max 40 requests per 10 seconds)
- Review logs in `storage/logs/laravel.log`

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

**Search not working / SQL column error**
- **Symptom**: Error message like `Column not found: 1054 Unknown column 'media.tags'`
- **Cause**: Scout database driver trying to search non-existent columns
- **Fix**: This has been fixed in the latest version. Ensure you're running the latest code where `Media::toSearchableArray()` properly converts array fields to strings
- **Alternative**: If using production, consider using Meilisearch or Algolia instead of the database driver:
  ```env
  SCOUT_DRIVER=meilisearch  # or algolia
  ```
- Clear search index if issues persist:
  ```bash
  php artisan scout:flush "App\Models\Media"
  php artisan scout:import "App\Models\Media"
  ```

**Platform availability not showing**
- Ensure TMDB API key is configured
- Re-run seeder to import media with platform data: `php artisan db:seed --class=TmdbMediaSeeder`
- Check that Platform model has records: `php artisan tinker` → `App\Models\Platform::count()`
- Verify `media_platform` pivot table has records

**Trending carousel not appearing**
- Ensure media records exist with `popularity` or `imdb_rating` values
- Check `$trending` variable is passed to home view in HomeController
- Clear view cache: `php artisan view:clear`
- Verify JavaScript is enabled in browser

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

## Future Plans

### Xtream Codes API - Postponed Features 📋

The Xtream Codes API implementation has been placed **on hold** until a future release (no ETA). The project is currently focused on:

**Current Focus:**
- 🎬 **TMDB-Based Content Catalog**: Enhanced browsing experience with latest movies, latest TV shows, and trending sections
- 📺 **TV Guide Enhancement**: Robust admin UI for channel management with EPG sync capabilities  
- 🎨 **Admin UI Improvements**: Modern left-sidebar navigation and improved visual design
- 👤 **User Experience**: Profile images in navbar and streamlined interactions

**Why the Change:**
WatchTheFlix is pivoting to provide a superior content discovery and browsing experience using TMDB's comprehensive database. The Xtream Codes functionality remains in the codebase for future development, but the current priorities are focused on features that deliver immediate value to users looking for a modern streaming platform experience.

**Future Consideration:**
- Xtream Codes API may be reconsidered in a future release after core features are fully polished
- The implementation remains available at [XTREAM_API.md](XTREAM_API.md) for reference
- Community feedback will help determine if and when to revive these features

## Database Seeding

### Overview

WatchTheFlix uses an intelligent seeding system that adapts based on your configuration:

#### Default Behavior (No API Keys)

Running `php artisan migrate --seed` without TMDB or EPG keys will create:
- ✅ Admin user (`admin@watchtheflix.com` / `password`)
- ✅ Production-ready forum categories
- ✅ Streaming platforms (Netflix, Prime, Hulu, Disney+, etc.)
- ✅ TV channels (UK and US)
- ✅ **Sample TV program data** (7 days of placeholder schedules)
- ❌ **No media content** (requires TMDB_API_KEY)

#### With TMDB API Key

Add `TMDB_API_KEY=your_key` to `.env` before seeding:
- ✅ Everything from default behavior
- ✅ **Top 50 movies from TMDB** (with full metadata, cast, posters, etc.)
- ✅ **Top 50 TV shows from TMDB** (with full metadata, cast, posters, etc.)
- ✅ **Streaming platform associations** (automatically linked from TMDB watch providers)

#### With EPG Provider URL

Add `EPG_PROVIDER_URL=https://your-provider.com/xmltv.xml` to `.env` before seeding:
- ✅ Everything from default behavior
- ✅ **Real EPG data** instead of sample TV programs
- ✅ Live TV schedules from your EPG provider
- ✅ Automatic channel-to-program matching

#### With Both TMDB and EPG

The ultimate setup - configure both before seeding:
```env
TMDB_API_KEY=your_tmdb_key
EPG_PROVIDER_URL=https://your-epg-provider.com/xmltv.xml
```

Result:
- ✅ Complete production-ready database
- ✅ Real movies and TV shows from TMDB
- ✅ Real TV schedules from EPG provider
- ✅ Ready for immediate use

### Seeder Components

#### DatabaseSeeder (Main Orchestrator)
Intelligently coordinates all seeders based on configuration:
- Creates admin user and forum categories
- Calls PlatformSeeder and TvChannelSeeder
- **Conditionally** calls TmdbMediaSeeder if TMDB key configured
- **Conditionally** fetches EPG data or seeds sample programs

#### PlatformSeeder
Seeds streaming platforms:
- Netflix, Amazon Prime Video, Hulu, Disney+, HBO Max, Apple TV+
- Paramount+, Peacock, BBC iPlayer, ITV Hub, Channel 4/5
- Sky Go, Now TV, BritBox, YouTube, Tubi, Crunchyroll

#### TvChannelSeeder
Seeds TV channels:
- **UK Channels**: BBC One, BBC Two, ITV, Channel 4/5, Sky One, etc.
- **US Channels**: ABC, CBS, NBC, FOX, HBO, ESPN, CNN, etc.

#### TmdbMediaSeeder
Fetches real content from TMDB (requires API key):
- Top 50 popular movies with full details
- Top 50 popular TV shows with full details
- Includes cast, crew, production companies, budgets, external IDs
- Automatically links streaming platforms from TMDB watch providers

#### TvProgramSeeder (Fallback)
Generates sample TV program data when EPG_PROVIDER_URL is not set:
- 7 days of placeholder schedules
- Realistic program durations (30-120 minutes)
- Time-appropriate titles (morning shows, evening news, etc.)
- Multiple genres and ratings

### Manual Seeding

Run individual seeders as needed:

```bash
# Seed only streaming platforms
php artisan db:seed --class=PlatformSeeder

# Seed only TV channels
php artisan db:seed --class=TvChannelSeeder

# Seed TMDB content (requires API key)
php artisan db:seed --class=TmdbMediaSeeder

# Seed sample TV programs (fallback)
php artisan db:seed --class=TvProgramSeeder

# Seed everything (respects configuration)
php artisan db:seed
```

### Production Recommendations

For production deployments:

1. **Set TMDB_API_KEY in .env** before initial seeding
   - Get your free API key at https://www.themoviedb.org/settings/api
   - Ensures rich media catalog from the start

2. **Set EPG_PROVIDER_URL in .env** before initial seeding
   - Provides real TV schedules instead of placeholders
   - Set up cron job for automated daily updates

3. **Change admin password immediately** after seeding
   - Default: `admin@watchtheflix.com` / `password`
   - Change via `/admin/settings` or user profile

4. **Enable Laravel scheduler** for automated updates:
   ```bash
   * * * * * cd /path/to/WatchTheFlix && php artisan schedule:run >> /dev/null 2>&1
   ```

### Seeder Changes (This PR)

**What Changed:**
- ❌ **Removed**: Sample media data (The Matrix, Inception, etc.)
- ✅ **Added**: Conditional TMDB seeding based on API key
- ✅ **Added**: Conditional EPG fetching based on provider URL
- ✅ **Enhanced**: TMDB API key now defaults to .env with admin panel override
- ✅ **Improved**: Clear messaging about what's being seeded and why

**Migration Path:**
If you're updating from an older version:
1. Run `php artisan migrate:fresh --seed` to get the new seeding behavior
2. Configure TMDB_API_KEY and EPG_PROVIDER_URL in .env for full functionality
3. Or add TMDB key via Admin Panel after seeding (overrides .env)

**Benefits:**
- 🎯 Production-ready from the start (no placeholder content)
- 🔧 Flexible configuration (works with or without API keys)
- 📊 Real content when configured (TMDB + EPG integration)
- ⚡ Fast seeding when keys not set (minimal data)

## Roadmap

### Recently Completed ✅ (December 2024)
- [x] **Redesigned Admin Panel**: Modern left-sidebar navigation with improved visual clarity
- [x] **TV Channel Admin UI**: Full CRUD interface for managing TV channels with EPG sync
- [x] **Enhanced TMDB Scraper**: Support for trending content scraping alongside movies and TV shows
- [x] **Home Page Refactor**: Dedicated sections for Latest Movies, Latest TV Shows, and Trending
- [x] **User Avatar in Navbar**: Display uploaded profile images in navigation bar
- [x] **Xtream Codes Feature Hold**: Properly documented postponement with clear future plans

### Previously Completed ✅
- [x] **TV Guide**: Browse UK and US TV channels with program schedules
- [x] **Platform Availability**: See which streaming services offer each title
- [x] **TMDB API Integration**: Import rich metadata from The Movie Database
- [x] **Platform Management**: Assign and display streaming platforms for content
- [x] **Forum System**: Custom-built forum with categories, threads, and replies
- [x] **Forum Subscriptions**: Subscribe to threads and get in-app notifications
- [x] **View Tracking**: Track thread views and engagement
- [x] **Parental Controls**: PIN-protected content restrictions
- [x] **Email Notifications**: Send email notifications for important events
- [x] **Two-Factor Authentication (2FA)**: Add an extra layer of account security
- [x] **Automated EPG Updates**: Real-time TV guide data integration from external sources
- [x] **Advanced Search**: Enhanced search with filters for genre, year, rating, platform
- [x] **Platform-Based Filtering**: Filter media by streaming service availability
- [x] **Subtitle Support**: Multi-language subtitle parsing and display in video player
- [x] **Multi-Language UI**: Internationalization support for the entire interface
- [x] **Social Sharing**: Share content to social media platforms
- [x] **Playlist Creation**: Create and manage custom playlists
- [x] **Advanced Analytics**: Comprehensive admin dashboard with user engagement metrics
- [x] **TMDB Bulk Import UI**: Admin interface for bulk importing content from TMDB

### Future Enhancements 🚀
- [ ] **Watch Party**: Synchronized viewing with friends
- [ ] **Mobile Apps**: Native iOS and Android applications
- [ ] **Chromecast/AirPlay**: Cast content to TVs and streaming devices
- [ ] **Content Recommendations**: AI-powered personalized recommendations
- [ ] **User Reviews**: Full review system with helpful votes
- [ ] **Advanced Parental Controls**: Content rating-based automatic restrictions

---

**Built with ❤️ using Laravel 12**