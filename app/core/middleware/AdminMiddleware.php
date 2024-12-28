<?php

namespace App\Core\Middleware;

class AdminMiddleware extends BaseMiddleware
{
    public function handle()
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
            echo "Not Access to page";
            return false;
        }
    }
}
