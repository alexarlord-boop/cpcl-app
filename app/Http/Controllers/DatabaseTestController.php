<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DatabaseTestController extends Controller
{
    public function testDatabaseConnection()
    {
        try {
            DB::connection()->getPdo();
            return "Database connection successful!";
        } catch (\Exception $e) {
            return "Database connection failed: " . $e->getMessage();
        }
    }
}
