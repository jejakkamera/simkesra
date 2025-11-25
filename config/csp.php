<?php

use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;

return [

    /*
     * Presets will determine which CSP headers will be set. A valid CSP preset is
     * any class that implements `Spatie\Csp\Preset`
     */
    'presets' => [
        Spatie\Csp\Presets\Basic::class,
    ],

    /**
     * Register additional global CSP directives here.
     * Follow Spatie v3 format for proper configuration
     * 
     * SOLUTION: Using nonce-based CSP for strict security
     * - Livewire/Alpine.js components use nonces for inline content
     * - External scripts loaded from CDN
     * - Maintains XSS protection without breaking functionality
     */
    'directives' => [
        [Directive::BASE, [Keyword::SELF]],
        [Directive::DEFAULT, [Keyword::SELF]],
        
        // SCRIPT: Nonce-based with external CDN + unsafe-hashes for Alpine.js
        // - 'nonce' for intentional inline scripts
        // - 'unsafe-hashes' for Alpine.js and dynamic scripts (more secure than unsafe-inline)
        // This allows only scripts with matching hash, preventing XSS
        [Directive::SCRIPT, [
            Keyword::SELF,
            Keyword::UNSAFE_HASHES,  // Allow only hashed inline scripts
            'https://www.google.com',
            'https://www.gstatic.com',
            'https://cdnjs.cloudflare.com',
            'https://cdn.jsdelivr.net',
            'https://maxcdn.bootstrapcdn.com',
            'https://cdn.datatables.net',
            'https://cdn.livewire.laravel.com',
        ]],
        
        // STYLE: Nonce-based with external CSS + unsafe-hashes
        // - 'nonce' for intentional inline styles
        // - 'unsafe-hashes' for component-generated styles (more secure than unsafe-inline)
        [Directive::STYLE, [
            Keyword::SELF,
            Keyword::UNSAFE_HASHES,  // Allow only hashed inline styles
            'https://fonts.googleapis.com',
            'https://cdn.jsdelivr.net',
            'https://cdnjs.cloudflare.com',
            'https://maxcdn.bootstrapcdn.com',
            'https://cdn.datatables.net',
        ]],
        
        [Directive::IMG, [Keyword::SELF, 'data:', 'https:']],
        [Directive::FONT, [Keyword::SELF, 'https://fonts.gstatic.com']],
        [Directive::CONNECT, [
            Keyword::SELF,
            'https://www.google.com',
            'https://cdnjs.cloudflare.com',
            'https://cdn.jsdelivr.net',
            'https://cdn.livewire.laravel.com',
        ]],
        [Directive::MEDIA, [Keyword::SELF]],
        [Directive::OBJECT, [Keyword::NONE]],
        [Directive::FRAME_ANCESTORS, [Keyword::SELF, 'https://www.google.com', 'https://www.gstatic.com']],
        [Directive::FORM_ACTION, [Keyword::SELF]],
        [Directive::FRAME, ['https://www.google.com/recaptcha/', 'https://recaptcha.google.com/']],
    ],

    /*
     * These presets which will be put in a report-only policy. This is great for testing out
     * a new policy or changes to existing CSP policy without breaking anything.
     */
    'report_only_presets' => [
        //
    ],

    /**
     * Register additional global report-only CSP directives here.
     */
    'report_only_directives' => [
        // [Directive::SCRIPT, [Keyword::UNSAFE_EVAL, Keyword::UNSAFE_INLINE]],
    ],

    /*
     * All violations against a policy will be reported to this url.
     * A great service you could use for this is https://report-uri.com/
     */
    'report_uri' => env('CSP_REPORT_URI', ''),

    /*
     * Headers will only be added if this setting is set to true.
     */
    'enabled' => env('CSP_ENABLED', true),

    /**
     * Headers will be added when Vite is hot reloading.
     */
    'enabled_while_hot_reloading' => env('CSP_ENABLED_WHILE_HOT_RELOADING', false),

    /*
     * The class responsible for generating the nonces used in inline tags and headers.
     */
    'nonce_generator' => Spatie\Csp\Nonce\RandomString::class,

    /*
     * ENABLED: Automatic nonce generation for strict CSP security.
     * 
     * This allows inline scripts and styles (like Livewire/Alpine.js)
     * without using unsafe-inline keyword. Each inline element gets a unique nonce.
     * This is the recommended approach for production security.
     */
    'nonce_enabled' => env('CSP_NONCE_ENABLED', true),
];
