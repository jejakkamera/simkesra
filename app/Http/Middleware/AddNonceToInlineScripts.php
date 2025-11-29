<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Csp\Nonce\NonceGenerator;

class AddNonceToInlineScripts
{
    /**
     * Handle an incoming request.
     * 
     * This middleware adds nonce attributes to inline script tags to support
     * Content-Security-Policy nonce-based approach. This allows us to use
     * strict CSP in production without unsafe-inline.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only process HTML responses
        if (!$this->isHtmlResponse($response)) {
            return $response;
        }

        // Get the nonce from Spatie CSP package
        $nonce = $this->getNonce();
        
        if (!$nonce) {
            return $response;
        }

        // Get response content
        $content = $response->getContent();
        
        if (empty($content)) {
            return $response;
        }

        // Add nonce to inline scripts
        $content = $this->addNonceToScripts($content, $nonce);
        
        // Add nonce to event listeners (for Livewire)
        $content = $this->addNonceToEventListeners($content, $nonce);

        $response->setContent($content);

        return $response;
    }

    /**
     * Get the nonce value from the CSP package.
     */
    private function getNonce(): ?string
    {
        // Try Spatie CSP v3+ first
        if (app()->has('csp-nonce')) {
            return app('csp-nonce');
        }

        // Try getting from NonceGenerator if available
        try {
            if (app()->has(NonceGenerator::class)) {
                $generator = app(NonceGenerator::class);
                return $generator->generate();
            }
        } catch (\Exception $e) {
            // Silent fail
        }

        return null;
    }

    /**
     * Add nonce to all inline <script> tags.
     */
    private function addNonceToScripts(string $content, string $nonce): string
    {
        // Pattern: <script ...> (not already with nonce, not external scripts)
        $pattern = '/<script(?!\s+nonce=)(?!\s+src=)([^>]*)>/i';
        $replacement = '<script nonce="' . e($nonce) . '"$1>';
        
        return preg_replace($pattern, $replacement, $content);
    }

    /**
     * Add nonce to Livewire Alpine.js @-prefixed event listeners.
     * 
     * Alpine.js event listeners like @click might be evaluated. Some older
     * Livewire versions might have inline event code that needs nonce.
     * This is a safety measure for compatibility.
     */
    private function addNonceToEventListeners(string $content, string $nonce): string
    {
        // This is optional - modern Livewire 3.5+ doesn't need this
        // But keeping for compatibility with dynamic content
        
        // Look for Livewire script tags specifically
        $pattern = '/<script\s+type=["\']text\/javascript["\'](?!\s+nonce=)([^>]*)>/i';
        $replacement = '<script type="text/javascript" nonce="' . e($nonce) . '"$1>';
        
        return preg_replace($pattern, $replacement, $content);
    }

    /**
     * Check if response is HTML.
     */
    private function isHtmlResponse($response): bool
    {
        $contentType = $response->headers->get('Content-Type', '');
        return strpos($contentType, 'text/html') !== false;
    }
}
