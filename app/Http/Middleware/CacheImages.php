<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class CacheImages
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Cek apakah request adalah untuk gambar
        if ($this->isImage($request->path())) {
            $cacheKey = 'image_' . md5($request->fullUrl());
            
            // Cek apakah gambar sudah ada di cache
            if (!Cache::has($cacheKey)) {
                // Simpan response ke cache selama 1 minggu
                Cache::put($cacheKey, $response->getContent(), now()->addWeek());
            }
            
            return response(Cache::get($cacheKey))
                ->header('Content-Type', $this->getImageMimeType($request->path()))
                ->header('Cache-Control', 'public, max-age=604800');
        }

        return $response;
    }

    private function isImage($path)
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        return in_array($extension, $imageExtensions);
    }

    private function getImageMimeType($path)
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
        ];
        return $mimeTypes[$extension] ?? 'application/octet-stream';
    }
}
