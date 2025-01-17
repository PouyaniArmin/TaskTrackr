<?php

namespace App\Core\Middleware;

class AdminMiddleware extends BaseMiddleware
{
    public function handle()
    {
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== 'Admin') {
            header('Location:/sigup');
            return false;
        }
        return true;
    }
}
