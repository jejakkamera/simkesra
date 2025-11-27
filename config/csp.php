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
     * SOLUTION: Using conditional CSP based on APP_ENV
     * - Development: unsafe-inline + unsafe-eval for Livewire compatibility
     * - Production: only unsafe-inline (unsafe-eval removed for security)
     * - Can further tighten with nonce-based or strict-dynamic approach
     */
    'directives' => [
        [Directive::BASE, [Keyword::SELF]],
        [Directive::DEFAULT, [Keyword::SELF]],
        
        // SCRIPT: Conditional unsafe-eval only for development
        [Directive::SCRIPT, array_merge(
            [
                Keyword::SELF,
                Keyword::UNSAFE_INLINE,  // Framework-generated scripts
            ],
            // Only add unsafe-eval in development (APP_ENV=local or development)
            env('APP_ENV') === 'production' ? [] : [Keyword::UNSAFE_EVAL],
            [
                'https://www.google.com',
                'https://www.gstatic.com',
                'https://cdnjs.cloudflare.com',
                'https://cdn.jsdelivr.net',
                'https://maxcdn.bootstrapcdn.com',
                'https://cdn.datatables.net',
                'https://cdn.livewire.laravel.com',
            ]
        )],
        
        // STYLE: Allow unsafe-inline (nonce is ignored for styles anyway)
        // When nonce is present in CSP, unsafe-inline is ignored by browsers
        // So we need to explicitly allow it OR remove nonce from style-src
        [Directive::STYLE, [
            Keyword::SELF,
            Keyword::UNSAFE_INLINE,  // Styles need this since nonce makes it ignored
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
     * DISABLED: Nonce generation disabled for now to debug middleware issue.
     * 
     * When nonce_enabled is true but middleware doesn't properly inject nonce,
     * framework scripts fail with CSP violations. Disabling nonce for now to get
     * app working, then we'll fix middleware to properly inject nonce.
     */
    'nonce_enabled' => env('CSP_NONCE_ENABLED', false),
];
