# Xtream Codes API Documentation

WatchTheFlix now includes a fully compatible Xtream Codes API implementation for IPTV applications and players.

## Base URL

All API endpoints are available at:
```
https://your-domain.com/api/xtream/
```

## Authentication

All requests require username and password parameters. Use your WatchTheFlix email and password for authentication.

## Endpoints

### 1. Player API (player_api.php)

The main endpoint compatible with Xtream Codes applications.

**Endpoint:** `GET/POST /api/xtream/player_api.php`

**Parameters:**
- `username` - Your email address
- `password` - Your account password
- `action` - Action to perform (optional)

**Available Actions:**

#### Get User Info (default)
```
GET /api/xtream/player_api.php?username=user@example.com&password=yourpassword
```

Returns user and server information.

#### Get Live Categories
```
GET /api/xtream/player_api.php?username=user@example.com&password=yourpassword&action=get_live_categories
```

Returns available live TV categories (countries).

#### Get Live Streams
```
GET /api/xtream/player_api.php?username=user@example.com&password=yourpassword&action=get_live_streams
GET /api/xtream/player_api.php?username=user@example.com&password=yourpassword&action=get_live_streams&category_id=UK
```

Returns live TV channels, optionally filtered by category.

#### Get VOD Categories
```
GET /api/xtream/player_api.php?username=user@example.com&password=yourpassword&action=get_vod_categories
```

Returns VOD categories (Movies, Series).

#### Get VOD Streams
```
GET /api/xtream/player_api.php?username=user@example.com&password=yourpassword&action=get_vod_streams
GET /api/xtream/player_api.php?username=user@example.com&password=yourpassword&action=get_vod_streams&category_id=movie
```

Returns VOD content, optionally filtered by category.

#### Get VOD Info
```
GET /api/xtream/player_api.php?username=user@example.com&password=yourpassword&action=get_vod_info&vod_id=123
```

Returns detailed information about a specific VOD item.

#### Get Series Info
```
GET /api/xtream/player_api.php?username=user@example.com&password=yourpassword&action=get_series_info&series_id=123
```

Returns detailed information about a specific series.

### 2. M3U Playlist Generation

**Endpoint:** `GET /api/xtream/playlist.m3u` or `/api/xtream/get.php`

**Parameters:**
- `username` - Your email address
- `password` - Your account password

**Example:**
```
GET /api/xtream/playlist.m3u?username=user@example.com&password=yourpassword
```

Returns an M3U playlist file containing all available channels and VOD content.

### 3. EPG (Electronic Program Guide) XML

**Endpoint:** `GET /api/xtream/epg.xml` or `/api/xtream/xmltv.php`

**Example:**
```
GET /api/xtream/epg.xml
GET /api/xtream/xmltv.php
```

Returns EPG data in XMLTV format for TV guide integration.

### 4. Stream URLs

#### Live TV Stream
```
GET /api/xtream/live/{username}/{password}/{stream_id}.m3u8
GET /api/xtream/live/{username}/{password}/{stream_id}.ts
```

#### VOD Stream
```
GET /api/xtream/vod/{username}/{password}/{stream_id}.mp4
GET /api/xtream/vod/{username}/{password}/{stream_id}.mkv
```

#### Series Stream
```
GET /api/xtream/series/{username}/{password}/{stream_id}.mp4
```

### 5. Server Info

**Endpoint:** `GET /api/xtream/server_info`

**Parameters:**
- `username` - Your email address
- `password` - Your account password

Returns server configuration and status information.

## Response Formats

All API responses are in JSON format, except for:
- M3U playlists (text/plain)
- EPG XML (application/xml)

## Example Responses

### User Info
```json
{
  "user_info": {
    "username": "user@example.com",
    "password": "api-token",
    "message": "Welcome to WatchTheFlix",
    "auth": 1,
    "status": "Active",
    "exp_date": null,
    "is_trial": "0",
    "active_cons": "0",
    "created_at": 1234567890,
    "max_connections": "1",
    "allowed_output_formats": ["m3u8", "ts"]
  },
  "server_info": {
    "url": "https://your-domain.com",
    "port": "80",
    "https_port": "443",
    "server_protocol": "https",
    "rtmp_port": "1935",
    "timezone": "UTC",
    "timestamp_now": 1234567890,
    "time_now": "2024-12-08 00:00:00"
  }
}
```

### Live Streams
```json
[
  {
    "num": 1,
    "name": "BBC One",
    "stream_type": "live",
    "stream_id": 1,
    "stream_icon": "https://example.com/logo.png",
    "epg_channel_id": "bbc-one",
    "added": 1234567890,
    "category_id": "UK",
    "tv_archive": 0,
    "tv_archive_duration": 0
  }
]
```

### VOD Streams
```json
[
  {
    "num": 1,
    "name": "Movie Title",
    "stream_type": "movie",
    "stream_id": 1,
    "stream_icon": "https://example.com/poster.jpg",
    "rating": 8.5,
    "rating_5based": 4.25,
    "added": 1234567890,
    "category_id": "movie",
    "container_extension": "mp4"
  }
]
```

## IPTV Player Configuration

### TiviMate Configuration
1. Add playlist using "Xtream Codes API"
2. Server URL: `https://your-domain.com/api/xtream`
3. Username: Your email
4. Password: Your password

### Perfect Player Configuration
1. Settings → General → Playlist
2. Add playlist
3. Playlist type: Xtream Codes
4. Server: `https://your-domain.com/api/xtream`
5. Username: Your email
6. Password: Your password

### GSE Smart IPTV Configuration
1. Add "Xtream Codes" playlist
2. Server URL: `https://your-domain.com/api/xtream/player_api.php`
3. Username: Your email
4. Password: Your password

### Alternative M3U URL Method
For players that only support M3U URLs:
```
https://your-domain.com/api/xtream/playlist.m3u?username=your@email.com&password=yourpassword
```

## EPG Configuration

To add EPG data to your IPTV player:
```
https://your-domain.com/api/xtream/epg.xml
```

## Security Notes

- Authentication uses your WatchTheFlix credentials
- API tokens are generated automatically upon first authentication
- Tokens are stored securely using Laravel Sanctum
- All API requests should be made over HTTPS in production

## Rate Limiting

API requests are subject to Laravel's default rate limiting:
- 60 requests per minute for authenticated users
- Consider implementing caching for frequently accessed data

## Support

For issues or questions about the Xtream API:
1. Check the main README.md for general platform documentation
2. Review API responses for error messages
3. Ensure your credentials are correct
4. Verify your account has active status

## Compatibility

This API is compatible with:
- ✅ TiviMate
- ✅ Perfect Player
- ✅ GSE Smart IPTV
- ✅ IPTV Smarters
- ✅ Kodi (with appropriate add-ons)
- ✅ VLC Media Player (M3U support)
- ✅ Any Xtream Codes compatible application
