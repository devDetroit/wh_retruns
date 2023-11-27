<?php

namespace App\Http\Controllers;

use App\Models\PartRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    function index()
    {
        return view('newcaliper.report.index');
    }

    public function detallado()
    {
        return view('newcaliper.report.detallado');
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

    public function getDataDetallado(Request $request)
    {
        $fechai = new Carbon($request->busqueda['fechai'] ?? date('Y-m-d'));
        $fechaf = new Carbon($request->busqueda['fechaf'] ?? date('Y-m-d'));
        return DB::select('CALL reportSerialNumberCaliper(?,?,?)', [$fechai, $fechaf, 'caliper']);
        /*   return $request; */
    }
}
