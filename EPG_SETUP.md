# EPG (Electronic Program Guide) Setup Guide

**Status**: ✅ Implemented with automated daily updates

---

## Overview

WatchTheFlix includes an automated EPG (Electronic Program Guide) update system that fetches TV program schedules from external XMLTV providers and updates the database daily.

---

## Features

✅ **Automated Updates**: Scheduled to run daily at 3:00 AM  
✅ **XMLTV Format Support**: Standard XMLTV XML format parsing  
✅ **Multi-Channel Support**: UK and US TV channels  
✅ **Error Handling**: Comprehensive logging and error recovery  
✅ **Manual Updates**: Command-line tool for on-demand updates  
✅ **Duplicate Prevention**: Skips existing programs to avoid duplicates  

---

## Configuration

### 1. Set EPG Provider URL

Add your EPG provider URL to the `.env` file:

```env
EPG_PROVIDER_URL=https://example.com/epg/xmltv.xml
```

**Popular EPG Sources**:
- **XMLTV.org**: Community-maintained EPG data
- **EPG123**: Schedules Direct integration
- **IPTV-EPG**: IPTV-focused EPG providers
- **Custom XMLTV**: Your own XMLTV feed

### 2. Verify Cron Job

The EPG update is scheduled in `routes/console.php`:

```php
Schedule::command('epg:update')->daily()->at('03:00');
```

**Ensure the Laravel scheduler is running**:

```bash
# Add to crontab (crontab -e)
* * * * * cd /path/to/watchtheflix && php artisan schedule:run >> /dev/null 2>&1
```

---

## Usage

### Automatic Updates

Once configured, EPG data will automatically update every day at 3:00 AM server time.

**Verify scheduled tasks**:
```bash
php artisan schedule:list
```

### Manual Updates

Run an immediate EPG update:

```bash
# Standard update (skips existing programs)
php artisan epg:update

# Force update (overwrites existing programs)
php artisan epg:update --force
```

### Check Logs

EPG updates are logged to `storage/logs/laravel.log`:

```bash
# View recent EPG logs
tail -f storage/logs/laravel.log | grep -i epg

# Search for errors
grep -i "EPG update failed" storage/logs/laravel.log
```

---

## XMLTV Format

The EPG system expects standard XMLTV format:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE tv SYSTEM "xmltv.dtd">
<tv>
  <!-- Channel definitions -->
  <channel id="bbc-one">
    <display-name>BBC One</display-name>
  </channel>
  
  <!-- Program listings -->
  <programme start="20241208180000 +0000" stop="20241208190000 +0000" channel="bbc-one">
    <title>Evening News</title>
    <desc>Latest news and weather</desc>
    <category>News</category>
    <icon src="https://example.com/icon.jpg" />
  </programme>
</tv>
```

### Required Fields
- `programme[@start]` - Start time (YYYYMMDDHHmmss format)
- `programme[@stop]` - End time (YYYYMMDDHHmmss format)
- `programme[@channel]` - Channel ID
- `programme/title` - Program title

### Optional Fields
- `programme/desc` - Description
- `programme/category` - Genre/category
- `programme/icon[@src]` - Program image URL

---

## How It Works

### 1. Data Fetching

The `EpgService` class fetches EPG data from the configured URL:

```php
// app/Services/EpgService.php
public function fetchEpgData(string $url): array
{
    $response = Http::timeout(60)->get($url);
    $xmlContent = $response->body();
    return $this->parseXmltvData($xmlContent);
}
```

### 2. XML Parsing

The service parses XMLTV format:
- Extracts channel information
- Parses program schedules
- Converts XMLTV timestamps to Carbon dates
- Handles timezone offsets

### 3. Database Import

The `UpdateEpgDataCommand` imports parsed data:
- Matches programs to existing channels
- Creates new program entries
- Updates existing programs (with --force flag)
- Skips duplicates to prevent data redundancy

### 4. Channel Matching

Programs are matched to channels by:
1. Channel ID (if available)
2. Channel name (fallback)

**Important**: Channels must exist in the `tv_channels` table before EPG import.

---

## Troubleshooting

### Issue: "EPG provider URL not configured"

**Solution**: Set `EPG_PROVIDER_URL` in your `.env` file

```bash
# Add to .env
EPG_PROVIDER_URL=https://example.com/epg/xmltv.xml
```

### Issue: "No EPG data received from provider"

**Possible causes**:
1. Invalid or unreachable URL
2. Network connectivity issues
3. Provider downtime
4. Invalid XMLTV format

**Solutions**:
```bash
# Test URL manually
curl -I https://your-epg-provider.com/xmltv.xml

# Check Laravel logs
tail -n 100 storage/logs/laravel.log | grep EPG

# Test with verbose output
php artisan epg:update -v
```

### Issue: "Channel not found - Skipping"

**Cause**: EPG data references channels that don't exist in your database

**Solution**: Ensure channels are created first
```bash
# Seed TV channels
php artisan db:seed --class=TvChannelSeeder

# Or create channels manually via admin panel
```

### Issue: Programs not updating

**Possible causes**:
1. Scheduler not running
2. Duplicate programs being skipped
3. Configuration issue

**Solutions**:
```bash
# Verify scheduler is running
php artisan schedule:list

# Force update to overwrite duplicates
php artisan epg:update --force

# Check cron logs
grep CRON /var/log/syslog
```

---

## Advanced Configuration

### Multiple EPG Providers

To support multiple EPG providers, modify the command:

```php
// app/Console/Commands/UpdateEpgDataCommand.php

$providers = [
    'uk' => env('EPG_PROVIDER_URL_UK'),
    'us' => env('EPG_PROVIDER_URL_US'),
];

foreach ($providers as $country => $url) {
    if ($url) {
        $this->info("Updating {$country} EPG...");
        $epgData = $this->epgService->fetchEpgData($url);
        // ... process data
    }
}
```

### Custom Update Schedule

Modify the schedule in `routes/console.php`:

```php
// Every 6 hours
Schedule::command('epg:update')->everySixHours();

// Twice daily at 3 AM and 3 PM
Schedule::command('epg:update')->twiceDaily(3, 15);

// Weekly on Sundays at 2 AM
Schedule::command('epg:update')->weekly()->sundays()->at('02:00');
```

### EPG Data Retention

By default, all EPG data is kept indefinitely. To clean up old programs:

```php
// Create cleanup command
// app/Console/Commands/CleanupOldEpgCommand.php

TvProgram::where('end_time', '<', now()->subDays(7))->delete();
```

Schedule it:
```php
Schedule::command('epg:cleanup')->daily();
```

---

## Production Deployment

### Recommended Setup

1. **Configure EPG provider** in `.env`:
   ```env
   EPG_PROVIDER_URL=https://reliable-epg-provider.com/xmltv.xml
   ```

2. **Set up cron job** on the server:
   ```bash
   sudo crontab -e -u www-data
   # Add:
   * * * * * cd /var/www/WatchTheFlix && php artisan schedule:run >> /dev/null 2>&1
   ```

3. **Test initial import**:
   ```bash
   php artisan epg:update --force
   ```

4. **Monitor logs** for errors:
   ```bash
   tail -f storage/logs/laravel.log | grep EPG
   ```

5. **Set up alerts** (optional):
   - Configure log monitoring (Sentry, Papertrail)
   - Set up health checks for EPG freshness
   - Alert on failed updates

---

## Performance Considerations

### Large EPG Files

For large XMLTV files (>10MB):

1. **Increase PHP timeout**:
   ```ini
   ; php.ini
   max_execution_time = 300
   ```

2. **Increase HTTP timeout**:
   ```php
   // In EpgService.php
   Http::timeout(120)->get($url);
   ```

3. **Process in chunks**:
   ```php
   // Batch insert programs
   TvProgram::insert($programs);
   ```

### Database Optimization

Add indexes for better query performance:

```php
// Already included in migrations
$table->index(['tv_channel_id', 'start_time']);
$table->index('end_time');
```

---

## API Integration

### Recommended EPG Providers

#### Free Options
- **XMLTV.org**: Community-maintained, varies by region
- **Schedules Direct**: Free for personal use (US/Canada)

#### Paid Options
- **EPG123**: Schedules Direct integration ($25/year)
- **IPTV-EPG**: Various providers (pricing varies)
- **Gracenote**: Enterprise-grade ($$$)

### Custom EPG Feed

If you have your own EPG source:

1. Generate XMLTV format
2. Host on accessible URL
3. Configure in WatchTheFlix
4. Set appropriate update schedule

---

## FAQ

**Q: How often does EPG data update?**  
A: By default, daily at 3:00 AM. You can change this in `routes/console.php`.

**Q: Can I use multiple EPG providers?**  
A: Yes, but you'll need to modify the command to support multiple URLs.

**Q: What happens if EPG provider is down?**  
A: The command will log an error and exit gracefully. Old data remains until next successful update.

**Q: Can I import EPG data manually?**  
A: Yes, run `php artisan epg:update` anytime.

**Q: Do I need EPG data for the platform to work?**  
A: No, EPG is optional. You can manually seed TV programs or operate without a TV guide.

**Q: How long does an EPG update take?**  
A: Depends on file size. Typical update: 30 seconds to 2 minutes.

**Q: Will existing programs be overwritten?**  
A: No, unless you use the `--force` flag.

---

## Related Documentation

- [TV Guide Documentation](README.md#tv-guide)
- [XTREAM API Documentation](XTREAM_API.md)
- [Production Deployment](PRODUCTION.md)

---

## Support

For EPG-related issues:
1. Check logs: `storage/logs/laravel.log`
2. Verify configuration: `EPG_PROVIDER_URL` in `.env`
3. Test provider URL: `curl -I <url>`
4. Open an issue on GitHub with logs

---

**Last Updated**: December 8, 2024  
**Maintained By**: WatchTheFlix Development Team
