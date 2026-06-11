<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class AuthPageController extends Controller
{
    public function login()
    {
        return Inertia::render('Auth/Login');
    }
}
