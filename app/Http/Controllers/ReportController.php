<?php

namespace App\Http\Controllers;

use App\Models\PartRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    function index()
    {
        return view('newcaliper.report.index');
    }

    function getData(Request $request)
    {
        $fechai = new Carbon($request->busqueda['fechai']);
        $fechaf = new Carbon($request->busqueda['fechaf']);
        return  PartRecord::with('part.parttype')->whereHas('part.parttype', function ($query) use ($request) {
            $query->where('id', $request->busqueda['type']);
        })->whereBetween('created_at', [$fechai->format("Y-m-d 00:00:00"), $fechaf->format("Y-m-d 23:59:59")])->get();
        /*   return $request; */
    }
}
