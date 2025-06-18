<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Simply return the dashboard view without passing extra data.
        return view('admin.new_dashboard');
    }
}
