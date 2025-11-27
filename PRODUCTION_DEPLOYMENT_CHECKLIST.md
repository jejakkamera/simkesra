# 🚀 Production Deployment Checklist - SIMKESRA

**Last Updated:** November 25, 2025  
**Status:** Ready for Production

---

## 1️⃣ ENVIRONMENT CONFIGURATION

### .env Settings
```bash
# Critical - MUST be set to 'production'
APP_ENV=production
APP_DEBUG=false

# Security
APP_KEY=base64:xxxxx (already set, verify it exists)
HASH_DRIVER=bcrypt or argon2id (current: bcrypt)

# Database
DB_HOST=production_server_ip
DB_PORT=3306
DB_DATABASE=production_db_name
DB_USERNAME=prod_user (NOT root)
DB_PASSWORD=strong_password_here

# Mail (if using production mail)
MAIL_HOST=smtp.mailtrap.io or your_smtp_server
MAIL_PORT=465 or 587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_app_password
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="SIMKESRA"

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=error (not debug)

# Cache
CACHE_DRIVER=redis (recommended) or database
REDIS_HOST=redis_server
REDIS_PASSWORD=strong_password

# Session
SESSION_DRIVER=cookie or redis
SESSION_LIFETIME=120 (minutes)

# Queue (if using)
QUEUE_CONNECTION=redis or database

# Sanctum (API authentication)
SANCTUM_STATEFUL_DOMAINS=yourdomain.com,www.yourdomain.com

# Additional
APP_URL=https://yourdomain.com (MUST be HTTPS)
```

### ✅ Verification Commands
```bash
php artisan config:cache      # Cache config (DO THIS LAST)
php artisan route:cache       # Cache routes
php artisan view:clear        # Clear view cache
php artisan event:cache       # Cache events
php artisan migrate            # Run final migrations
```

---

## 2️⃣ DATABASE & MIGRATIONS

### Pre-Production
```bash
# Run migrations
php artisan migrate --force    # Use --force in production

# Seed initial data (if needed)
php artisan db:seed --class=YourSeeder

# Verify database
php artisan tinker
>>> DB::connection()->getPdo()   # Test connection
```

### Database Backup Strategy
```bash
# Automated daily backup (add to cron)
0 2 * * * cd /path/to/app && php artisan backup:run >> /var/log/laravel-backup.log 2>&1

# Manual backup before major changes
mysqldump -u username -p database_name > backup_$(date +\%Y\%m\%d).sql

# Verify backup
mysql -u username -p database_name < backup_20251125.sql (on test server)
```

---

## 3️⃣ SECURITY HARDENING

### CSP (Content Security Policy) - ✅ ALREADY CONFIGURED

**Status:** Production-ready with conditional configuration

#### Current Setup (Automatic)
```php
// config/csp.php automatically adjusts based on APP_ENV
if (APP_ENV === 'production') {
    script-src: 'self' 'unsafe-inline' [external domains] // NO unsafe-eval
    style-src:  'self' 'unsafe-inline' [external domains]
} else {
    script-src: 'self' 'unsafe-inline' 'unsafe-eval' [external domains] // Dev only
}
```

**Action:** Just set `APP_ENV=production` and CSP automatically becomes stricter! ✅

### HTTPS/SSL
```bash
# ✅ MUST have valid SSL certificate
# Use Let's Encrypt (free) or paid certificate

# Redirect HTTP to HTTPS (Nginx)
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    return 301 https://$server_name$request_uri;
}

# Verify SSL
openssl s_client -connect yourdomain.com:443

# Test SSL grade
# https://www.ssllabs.com/ssltest/analyze.html?d=yourdomain.com
```

### Security Headers
```php
// Already implemented via CSP middleware
// Add to nginx.conf or .htaccess if needed:

add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
add_header X-Content-Type-Options "nosniff" always;
add_header X-Frame-Options "SAMEORIGIN" always;
add_header X-XSS-Protection "1; mode=block" always;
add_header Referrer-Policy "strict-origin-when-cross-origin" always;
add_header Permissions-Policy "geolocation=(), microphone=(), camera=()" always;
```

### File Permissions
```bash
# Critical - Laravel requires specific permissions
chmod -R 755 /var/www/app               # Directory permissions
chmod -R 644 /var/www/app/config        # Config readable
chmod -R 755 /var/www/app/storage       # Storage writable
chmod -R 755 /var/www/app/bootstrap/cache
chmod -R 755 /var/www/app/public

# Storage directory MUST be writable by web server
chown -R www-data:www-data /var/www/app/storage
chown -R www-data:www-data /var/www/app/bootstrap/cache

# .env file - NOT readable by others
chmod 600 /var/www/app/.env
chown www-data:www-data /var/www/app/.env
```

### Environment Variables Protection
```bash
# ✅ NEVER commit .env to git
# ✅ Keep .env file outside web root or restrict access

# Nginx: deny access to .env
location ~ /\.env {
    deny all;
}

# Apache: .htaccess
<FilesMatch "^\.env">
    Order allow,deny
    Deny from all
</FilesMatch>
```

### Firewall & Access Control
```bash
# Allow only necessary ports
sudo ufw allow 22/tcp   # SSH
sudo ufw allow 80/tcp   # HTTP
sudo ufw allow 443/tcp  # HTTPS
sudo ufw deny 3306/tcp  # MySQL (internal only)

# SSH Key Authentication (not password)
ssh-keygen -t rsa -b 4096
# Add to ~/.ssh/authorized_keys
chmod 600 ~/.ssh/authorized_keys

# Disable root login
# In /etc/ssh/sshd_config:
PermitRootLogin no
PasswordAuthentication no
```

### Database Security
```bash
# Create dedicated database user (NOT root)
CREATE USER 'simkesra_prod'@'localhost' IDENTIFIED BY 'strong_password_here';
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, ALTER ON simkesra_production.* TO 'simkesra_prod'@'localhost';
FLUSH PRIVILEGES;

# Remove default users
DROP USER 'test'@'localhost';
DROP USER ''@'localhost';
FLUSH PRIVILEGES;

# Change MySQL root password
ALTER USER 'root'@'localhost' IDENTIFIED BY 'new_strong_password';
```

---

## 4️⃣ PERFORMANCE OPTIMIZATION

### Configuration Caching
```bash
# PRODUCTION ONLY - Cache everything
php artisan config:cache      # Cache config
php artisan route:cache       # Cache routes
php artisan event:cache       # Cache events
php artisan view:clear        # Pre-compile views

# Verify caching
php artisan config:show | head -20
php artisan route:list
```

### Asset Optimization
```bash
# Production build (minify CSS/JS)
npm run build               # Vite production build

# Verify build
ls -la public/build/
```

### Database Optimization
```bash
# Add indexes to frequently searched columns
php artisan make:migration add_indexes_to_tables

# In migration:
public function up() {
    Schema::table('users', function (Blueprint $table) {
        $table->index('email');
        $table->index('created_at');
    });
}

# Check query performance
EXPLAIN SELECT * FROM users WHERE email = 'test@example.com';
```

### PHP Optimization
```bash
# php.ini settings for production
memory_limit = 256M (or higher)
max_execution_time = 300
upload_max_filesize = 64M
post_max_size = 64M
opcache.enable = 1
opcache.memory_consumption = 128
opcache.max_accelerated_files = 10000
opcache.validate_timestamps = 0 (production only!)

# Verify settings
php -i | grep -E "memory_limit|opcache"
```

### Web Server Optimization
```nginx
# Nginx production settings
worker_processes auto;
keepalive_timeout 65;
client_max_body_size 64M;

# Gzip compression
gzip on;
gzip_vary on;
gzip_proxied any;
gzip_comp_level 6;
gzip_types text/plain text/css text/xml text/javascript application/json application/javascript;

# Browser caching
location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
    expires 1y;
    add_header Cache-Control "public, immutable";
}
```

---

## 5️⃣ MONITORING & LOGGING

### Logging Setup
```bash
# Laravel logging configuration
# .env:
LOG_CHANNEL=stack
LOG_LEVEL=error    # NOT debug in production

# Verify logs are being written
tail -f storage/logs/laravel.log

# Rotate logs (prevent disk fill)
php artisan log:clear
```

### Automated Monitoring
```bash
# Add monitoring tools:
1. Laravel Telescope (optional - can be disabled in prod)
2. Sentry for error tracking (https://sentry.io)
3. New Relic or DataDog for APM

# Sentry setup:
composer require sentry/sentry-laravel
php artisan vendor:publish --provider="Sentry\Laravel\ServiceProvider"
# Add SENTRY_DSN to .env
```

### Health Check Endpoint
```php
// routes/web.php
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now(),
        'database' => DB::connection()->getPdo() ? 'connected' : 'error'
    ]);
});

// Monitor this endpoint with:
# curl https://yourdomain.com/health
```

### System Monitoring
```bash
# Monitor server resources
free -h              # RAM usage
df -h               # Disk usage
top                 # Process monitor

# Add to monitoring dashboard:
- CPU usage
- Memory usage
- Disk space
- Database connections
- Error rates
```

---

## 6️⃣ BACKUP & DISASTER RECOVERY

### Automated Backups
```bash
# Install Laravel Backup package (already in project?)
composer require spatie/laravel-backup

# Configuration
php artisan vendor:publish --provider="Spatie\Backup\BackupServiceProvider"

# Setup scheduled backups (app/Console/Kernel.php)
$schedule->command('backup:run')->dailyAt('02:00');

# Verify backups
php artisan backup:list
```

### Backup Strategy
```
Daily backups: Keep 7 days
Weekly backups: Keep 4 weeks
Monthly backups: Keep 12 months

Storage: Cloud (S3, Google Drive, Backblaze) + Local
```

### Recovery Plan
```bash
# Restore from backup
php artisan backup:restore

# Verify restoration
php artisan migrate:status
php artisan tinker
>>> DB::table('users')->count()
```

---

## 7️⃣ TESTING & VALIDATION

### Pre-Deployment Testing
```bash
# Run all tests
php artisan test

# Test database
php artisan migrate:fresh --seed
php artisan tinker

# Test caches
php artisan cache:clear
php artisan config:cache

# Performance test
ab -n 1000 -c 10 https://yourdomain.com/

# Security scan
php artisan enlightn
```

### Deployment Verification
```bash
# 1. Test application loads
curl -I https://yourdomain.com

# 2. Test critical features
- Login page loads
- Dashboard accessible
- Data displays correctly
- Forms submit successfully

# 3. Test security
- HTTPS enforced
- CSP headers present
  curl -I https://yourdomain.com | grep -i "content-security"
- HSTS headers present
  curl -I https://yourdomain.com | grep -i "strict-transport"

# 4. Test performance
- Page load time < 2s
- No console errors
- No CSP violations
  # Check browser console
```

### Post-Deployment Checklist
```
✅ Application loads without errors
✅ Database connected and accessible
✅ Logs being written to storage/logs
✅ HTTPS working correctly
✅ CSP headers present and correct
✅ Email sending working
✅ File uploads working
✅ Authentication working
✅ All features functional
✅ No 500 errors
✅ Performance acceptable
```

---

## 📋 DEPLOYMENT DAY CHECKLIST

### Before Deployment
- [ ] Backup current production
- [ ] Test in staging environment
- [ ] Verify all credentials in .env
- [ ] Check disk space available
- [ ] Check database disk space
- [ ] Notify users of maintenance window (if needed)

### During Deployment
```bash
# 1. Go maintenance mode (optional)
php artisan down --message="We are updating. Please try again soon."

# 2. Pull latest code
git pull origin main

# 3. Install dependencies
composer install --no-dev --optimize-autoloader

# 4. Run migrations
php artisan migrate --force

# 5. Cache everything
php artisan config:cache
php artisan route:cache
php artisan event:cache

# 6. Clear application cache
php artisan cache:clear

# 7. Seed data if needed
php artisan db:seed --class=ProductionSeeder

# 8. Exit maintenance mode
php artisan up
```

### After Deployment
- [ ] Verify application loads
- [ ] Check error logs for issues
- [ ] Test critical features
- [ ] Monitor performance
- [ ] Check CSP reports
- [ ] Verify backups created

---

## 🔐 CSP PRODUCTION BEHAVIOR

**Automatic CSP Adjustment:**

When `APP_ENV=production`, the following happens automatically:

```php
// Development (current)
script-src 'self' 'unsafe-inline' 'unsafe-eval' https://...

// Production (automatic)
script-src 'self' 'unsafe-inline' https://...
// ↑ unsafe-eval REMOVED automatically
```

**No additional CSP changes needed!** The conditional logic in `config/csp.php` handles it. ✅

---

## 🚨 COMMON PRODUCTION ISSUES & SOLUTIONS

### Issue 1: Database Connection Fails
```bash
# Check credentials in .env
# Verify database server running
mysql -u root -p -h db.server.com

# Check Laravel can connect
php artisan tinker
>>> DB::connection()->getPdo()
```

### Issue 2: Permissions Error
```bash
# Fix file permissions
sudo chown -R www-data:www-data /var/www/app/storage
sudo chmod -R 755 /var/www/app/storage
```

### Issue 3: 500 Error
```bash
# Check error logs
tail -f storage/logs/laravel.log

# Clear cache
php artisan cache:clear
php artisan config:clear

# Check file permissions again
```

### Issue 4: High Memory Usage
```bash
# Check current config
php -i | grep memory_limit

# Monitor memory
free -h

# Optimize code / increase memory if needed
```

### Issue 5: Slow Performance
```bash
# Enable query logging
# In .env: LOG_QUERIES=true

# Check slow queries
tail -f storage/logs/laravel.log | grep "query"

# Add database indexes
# Check with EXPLAIN
```

---

## 📞 SUPPORT & RESOURCES

- **Laravel Docs:** https://laravel.com/docs
- **Livewire Docs:** https://livewire.laravel.com
- **Enlightn Docs:** https://laravel-enlightn.com
- **PHP Manual:** https://php.net
- **Nginx Docs:** https://nginx.org/en/docs/

---

## ✅ FINAL CHECKLIST SUMMARY

- [ ] Environment variables configured
- [ ] Database backed up and verified
- [ ] HTTPS/SSL certificate installed
- [ ] Security headers configured
- [ ] File permissions set correctly
- [ ] CSP configured (automatic with APP_ENV=production)
- [ ] Caches configured and warming
- [ ] Backups automated
- [ ] Monitoring set up
- [ ] Error logging configured
- [ ] All tests passing
- [ ] Performance verified
- [ ] Staging environment matches production
- [ ] Team trained on deployment process
- [ ] Rollback plan documented
- [ ] Post-deployment verification plan ready

---

**Status:** 🟢 READY FOR PRODUCTION

**Notes:** This checklist assumes standard VPS/Dedicated Server setup. For cloud platforms (AWS, Google Cloud, Azure), some steps may differ.
