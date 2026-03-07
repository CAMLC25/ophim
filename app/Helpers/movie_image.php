<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

function movie_image($url)
{
    if (!$url) return null;

    $filename = basename($url);
    $path = "movies/".$filename;

    // nếu đã có ảnh rồi thì dùng luôn
    if (Storage::disk('public')->exists($path)) {
        return asset("storage/".$path);
    }

    try {

        $response = Http::timeout(10)->get($url);

        if ($response->successful()) {

            Storage::disk('public')->put($path, $response->body());

            return asset("storage/".$path);
        }

    } catch (\Exception $e) {
        return $url;
    }

    return $url;
}
