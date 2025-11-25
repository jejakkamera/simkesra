<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AddNonceToInlineScripts
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only process HTML responses
        if (!$this->isHtmlResponse($response)) {
            return $response;
        }

        // Get the nonce from the container (set by Spatie CSP)
        // Only inject nonce if it exists in container
        if (!app()->has('csp-nonce')) {
            return $response;
        }

        $nonce = app('csp-nonce');

        if (!$nonce) {
            return $response;
        }

        // Get response content
        $content = $response->getContent();

        // Replace all <script> tags without nonce with nonce-enabled tags
        // This regex finds <script> or <script ...> (but not already with nonce)
        $pattern = '/<script(?!\s+nonce=)([^>]*)>/i';
        $replacement = '<script nonce="' . $nonce . '"$1>';
        
        $content = preg_replace($pattern, $replacement, $content);

        $response->setContent($content);

        return $response;
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
