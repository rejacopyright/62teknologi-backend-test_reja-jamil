<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\term;

class TermController extends Controller
{
    function getTerm()
    {
        return term::orderBy('name', 'asc')->paginate(10);
        // ->with(['children'])->paginate(10);
    }
}
