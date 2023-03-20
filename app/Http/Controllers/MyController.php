<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class MyController extends Controller
{
    public function convert()
    {
        // mas@yopmail.com
        dd(Hash::make('Gopin2315'));
    }
}
