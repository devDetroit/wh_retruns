<?php

namespace App\Http\Controllers;

use App\Models\PartType;
use Illuminate\Http\Request;

class PartTypesController extends Controller
{
    function getTypes()
    {
        return PartType::all();
    }
}
