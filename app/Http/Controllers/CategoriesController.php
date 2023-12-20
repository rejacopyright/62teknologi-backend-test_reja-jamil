<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\categories;

class CategoriesController extends Controller
{
    function getCategories()
    {
        return categories::orderBy('name', 'asc')->paginate(10);
        // ->with(['children'])->paginate(10);
    }
}
