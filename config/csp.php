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
     */
    'directives' => [
        [Directive::BASE, [Keyword::SELF]],
        [Directive::DEFAULT, [Keyword::SELF]],
        [Directive::SCRIPT, [Keyword::SELF, Keyword::UNSAFE_INLINE, Keyword::UNSAFE_EVAL, 'https://www.google.com', 'https://www.gstatic.com', 'https://cdnjs.cloudflare.com', 'https://cdn.jsdelivr.net', 'https://maxcdn.bootstrapcdn.com', 'https://cdn.datatables.net']],
        [Directive::STYLE, [Keyword::SELF, Keyword::UNSAFE_INLINE, 'https://fonts.googleapis.com', 'https://cdn.jsdelivr.net', 'https://cdnjs.cloudflare.com', 'https://maxcdn.bootstrapcdn.com', 'https://cdn.datatables.net']],
        [Directive::IMG, [Keyword::SELF, 'data:', 'https:']],
        [Directive::FONT, [Keyword::SELF, 'https://fonts.gstatic.com']],
        [Directive::CONNECT, [Keyword::SELF, 'https://www.google.com', 'https://cdnjs.cloudflare.com', 'https://cdn.jsdelivr.net']],
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
     * Set false to disable automatic nonce generation and handling.
     * This is useful when you want to use 'unsafe-inline' for scripts/styles
     * and cannot add inline nonces.
     * Note that this will make your CSP policy less secure.
     */
    'nonce_enabled' => env('CSP_NONCE_ENABLED', false),
];