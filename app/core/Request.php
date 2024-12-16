<?php

namespace App\Core;

class Request
{
    public function path(): string
    {
        $path = $_SERVER['REQUEST_URI'];
        $position = strpos($path, "?");
        if ($position !== false) {
            return rtrim(htmlspecialchars(substr($path, 0, $position)), '/');
        }
        return rtrim(htmlspecialchars($path), '/');
    }
}
