<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CountersController extends Controller
{
    public function index()
    {
        return view(
            'reman_labels.counter',
            [
                ""
            ]
        );
    }
}
