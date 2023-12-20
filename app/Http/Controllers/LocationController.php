<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\location;

class LocationController extends Controller
{
    function getLocation()
    {
        return location::orderBy('name', 'asc')->paginate(10);
        // ->with(['children'])->paginate(10);
    }
}
