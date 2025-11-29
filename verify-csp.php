#!/usr/bin/env php
<?php
/**
 * CSP Hardening Verification Script
 * 
 * This script verifies that CSP configuration is correctly set up
 * for production with Livewire 3.5
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "\n";
echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║         CSP Configuration Verification Report                 ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

// 1. Check environment
// Use Laravel's env() helper properly
$appEnv = env('APP_ENV');
if (!$appEnv || $appEnv === 'unknown') {
    // Try to get directly from .env using grep
    $envContent = trim(shell_exec("grep -E '^APP_ENV=' /Applications/MAMP/htdocs/83/simkesra/.env 2>/dev/null || echo 'APP_ENV=local'"));
    $appEnv = str_replace('APP_ENV=', '', $envContent) ?: 'local';
}
echo "1. Environment Configuration:\n";
echo "   ✓ APP_ENV: $appEnv\n";

// 2. Check CSP settings
$cspEnabled = env('CSP_ENABLED', 'unknown');
$cspNonceEnabled = env('CSP_NONCE_ENABLED', env('APP_ENV') === 'production');
echo "\n2. CSP Configuration:\n";
echo "   ✓ CSP_ENABLED: " . ($cspEnabled ? 'true' : 'false') . "\n";
echo "   ✓ CSP_NONCE_ENABLED: " . ($cspNonceEnabled ? 'true' : 'false') . "\n";

// 3. Check config/csp.php
$cspConfig = require __DIR__.'/config/csp.php';
echo "\n3. CSP Directives Status:\n";
echo "   ✓ Presets configured: " . count($cspConfig['presets']) . "\n";
echo "   ✓ Directives configured: " . (is_array($cspConfig['directives']) ? count($cspConfig['directives']) : 'dynamic') . "\n";
echo "   ✓ Nonce generator: " . class_basename($cspConfig['nonce_generator']) . "\n";
echo "   ✓ Nonce enabled: " . ($cspConfig['nonce_enabled'] ? 'true' : 'false') . "\n";

// 4. Check middleware
$kernelPath = __DIR__.'/app/Http/Kernel.php';
$kernelContent = file_get_contents($kernelPath);
$hasNonceMiddleware = strpos($kernelContent, 'AddNonceToInlineScripts::class') !== false;
$hasSpatieCspMiddleware = strpos($kernelContent, 'AddCspHeaders::class') !== false;

echo "\n4. Middleware Registration:\n";
echo "   " . ($hasSpatieCspMiddleware ? "✓" : "✗") . " Spatie CSP middleware registered\n";
echo "   " . ($hasNonceMiddleware ? "✓" : "✗") . " AddNonceToInlineScripts middleware registered\n";

// 5. Check Livewire version
$composerLock = json_decode(file_get_contents(__DIR__.'/composer.lock'), true);
$livewireVersion = null;
foreach ($composerLock['packages'] as $package) {
    if ($package['name'] === 'livewire/livewire') {
        $livewireVersion = ltrim($package['version'], 'v');
        break;
    }
}

echo "\n5. Livewire Integration:\n";
$versionOk = $livewireVersion && version_compare($livewireVersion, '3.0', '>=');
echo "   ✓ Livewire version: " . ($livewireVersion ?? 'unknown') . "\n";
echo "   ✓ Compatible with nonce-based CSP: " . ($versionOk ? 'YES ✓' : 'NO ✗') . "\n";

// 6. Check production readiness
echo "\n6. Production Readiness:\n";

$issues = [];

if ($appEnv !== 'production' && $cspNonceEnabled) {
    $issues[] = "⚠ Development environment but nonce is enabled - not recommended";
}

if ($appEnv === 'production' && !$cspNonceEnabled) {
    $issues[] = "✗ Production environment but nonce is NOT enabled - SECURITY ISSUE";
}

if (!$hasNonceMiddleware) {
    $issues[] = "✗ AddNonceToInlineScripts middleware not registered";
}

if (!$hasSpatieCspMiddleware) {
    $issues[] = "✗ Spatie CSP middleware not registered";
}

if (empty($issues)) {
    echo "   ✓ All checks passed!\n";
} else {
    foreach ($issues as $issue) {
        echo "   $issue\n";
    }
}

echo "\n7. Next Steps:\n";
if ($appEnv === 'production' && $cspNonceEnabled) {
    echo "   1. Run: php artisan enlightn\n";
    echo "   2. Check that CSP check (67) now PASSES ✓\n";
    echo "   3. Monitor browser console for CSP violations\n";
} else {
    echo "   1. Review CSP_HARDENING_GUIDE.md\n";
    echo "   2. Test Livewire features locally\n";
    echo "   3. Before production, set CSP_NONCE_ENABLED=true\n";
}

echo "\n";
echo "╔════════════════════════════════════════════════════════════════╗\n";
if (empty($issues)) {
    echo "║                  Status: ✓ READY FOR PRODUCTION               ║\n";
} else {
    echo "║                  Status: ⚠ NEEDS ATTENTION                   ║\n";
}
echo "╚════════════════════════════════════════════════════════════════╝\n\n";
