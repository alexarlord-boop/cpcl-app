<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatabaseTestController extends Controller
{
    public function testDatabaseConnection()
    {
        Log::info('Route hit!');
        try {
            DB::connection()->getPdo();
            return "Database connection successful!";
        } catch (\Exception $e) {
            return "Database connection failed: " . $e->getMessage();
        }
    }
}
