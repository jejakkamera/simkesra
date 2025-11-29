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
     * PRODUCTION-HARDENED: Nonce-based CSP
     * 
     * Strategy:
     * - Development: unsafe-inline + unsafe-eval for debugging ease
     * - Production: Nonce-based CSP (no unsafe-inline/unsafe-eval)
     * 
     * How it works:
     * 1. Middleware adds nonce="<random>" to all inline <script> tags
     * 2. CSP header includes nonce-<value> in script-src directive
     * 3. Browser only executes scripts with matching nonce
     * 4. Livewire compatibility: Works seamlessly with Livewire 3.5
     */
    'directives' => array_merge(
        [
            [Directive::BASE, [Keyword::SELF]],
            [Directive::DEFAULT, [Keyword::SELF]],
        ],
        // SCRIPT directive changes based on environment
        env('APP_ENV') === 'production' ? [
            // PRODUCTION: Nonce-based CSP (hardened security)
            [Directive::SCRIPT, [
                Keyword::SELF,
                "nonce",  // Spatie auto-injects nonce value in header
                'https://www.google.com',
                'https://www.gstatic.com',
                'https://cdnjs.cloudflare.com',
                'https://cdn.jsdelivr.net',
                'https://maxcdn.bootstrapcdn.com',
                'https://cdn.datatables.net',
                'https://cdn.livewire.laravel.com',
                'https://cdn.tailwindcss.com',
            ]],
        ] : [
            // DEVELOPMENT: Permissive CSP for debugging
            [Directive::SCRIPT, [
                Keyword::SELF,
                Keyword::UNSAFE_INLINE,
                Keyword::UNSAFE_EVAL,
                'https://www.google.com',
                'https://www.gstatic.com',
                'https://cdnjs.cloudflare.com',
                'https://cdn.jsdelivr.net',
                'https://maxcdn.bootstrapcdn.com',
                'https://cdn.datatables.net',
                'https://cdn.livewire.laravel.com',
                'https://cdn.tailwindcss.com',
            ]],
        ],
        [
            // STYLE: Production-safe (nonce works for styles too, but Tailwind-generated means inline)
            [Directive::STYLE, [
                Keyword::SELF,
                Keyword::UNSAFE_INLINE,  // Tailwind/Vite output is inline, safe with CSP
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
        ]
    ),

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
     * ENABLED: Nonce generation for production security
     * 
     * - Development (CSP_NONCE_ENABLED=false): Uses unsafe-inline, easier debugging
     * - Production (CSP_NONCE_ENABLED=true): Uses nonce-based CSP, maximum security
     * 
     * Middleware (AddNonceToInlineScripts) injects nonce into all <script> tags
     */
    'nonce_enabled' => env('CSP_NONCE_ENABLED', env('APP_ENV') === 'production'),
];
