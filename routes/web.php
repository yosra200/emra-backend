<?php

use Illuminate\Support\Facades\Route;

// The site itself is a headless API, so the root URL points at the admin panel:
// Filament sends the visitor to its login page, or straight to the dashboard
// when they are already signed in.
Route::redirect('/', '/admin');
