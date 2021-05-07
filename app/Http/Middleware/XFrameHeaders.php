<?php namespace App\Http\Middleware;
use Closure;
class XFrameHeaders {
    public function handle($request, Closure $next)
    {
        $handle = $next($request);

        if(method_exists($handle, 'header'))
        {
            // Standard HTTP request.
    
            $handle->header('X-Frame-Options', 'SAMEORIGIN');
    
            return $handle;
        }
    
        // Download Request?
    
        $handle->headers->set('Some-Other-Header' , 'value');
    
        return $handle;
        
    }
}