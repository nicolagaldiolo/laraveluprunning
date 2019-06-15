<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ControllerB extends Controller
{
    public function home()
    {
        return "Hello, i'm API ControllerB";
    }
}
