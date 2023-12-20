<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\attributes;

class AttributesController extends Controller
{
    function getAttributes()
    {
        return attributes::orderBy('name', 'asc')->paginate(10);
        // ->with(['children'])->paginate(10);
    }
}
