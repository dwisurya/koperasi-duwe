<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class BeritaAcaraController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:berita-acara-list', only: ['index']),
        ];
    }

    public function index()
    {
        return view('rat.berita-acara');
    }
}
