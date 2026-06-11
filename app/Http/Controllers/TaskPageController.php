<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class TaskPageController extends Controller
{
    public function dashboard()
    {
        return Inertia::render('Dashboard');
    }
    
    public function index()
    {
        return Inertia::render('Tasks/Index');
    }

    public function create()
    {
        return Inertia::render('Tasks/Create');
    }

    public function show($id)
    {
        return Inertia::render('Tasks/Show');
    }

    public function edit($id)
    {
        return Inertia::render('Tasks/Edit');
    }
}
