<?php

namespace App\Http\Controllers;

use App\Models\Caliper;
use App\Models\CaliperLog;
use App\Models\Printer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CaliperController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    public function getPrinter()
    {
        return Printer::whereRelation('computer', 'computer_ip', request()->getClientIp())->get();
    }

    public function printLabel()
    {

          $printer = $this->getPrinter();
        $message = '';
        $returnValue = 0;
        $data = '';
        if (isset($printer[0])) {
            if ($printer[0]->printer == '192.168.80.19') {
                $digitos = strlen(request()->partNumber);
                if ($digitos < 8) {
                    $bclon = 5;
                    $by = 10;
                } else
                if ($digitos >= 8 && $digitos < 11) {
                    $bclon = 4;
                    $by = 50;
                } else if ($digitos > 10 && $digitos < 20) {
                    $bclon = 3;
                    $by = 80;
                }
                $conn = fsockopen($printer[0]->printer, 9100, $errno, $errstr);

                /*   if (Str::contains(request()->getClientIp(), '80') || Str::contains(request()->getClientIp(), '81') || Str::contains(request()->getClientIp(), '82')) {
                    $data = '^XA
                        ^FO85,37^A0,37^FD' . request()->family . '^FS
                        ^FO385,37^A0,37^FD' . request()->partNumber . '^FS
                        ^BY' . $bclon . ',2,65
                        ^FO65,80^BCN,80,N,N^FD' . request()->partNumber . '^FS
                        ^FO200,165^A0,22^FDMade in China^FS
                    ^XZ
                    ';
                } else { */
                $data = '^XA
                            ^FO50,30^A0,30^FD' . request()->family  . '^FS
                            ^FO400,30^A0,30^FD' . request()->partNumber . '^FS
                            ^BY' . $bclon . ',2,65
                            ^FO' . $by . ',60^BCN,120,N,N^FD' . request()->partNumber . '^FS
                            ^FO200,185^A0,32^FDMade in China^FS
                            ^XZ';
                /*  } */
                fputs($conn, $data, strlen($data));
                fclose($conn);
                $returnValue = 1;
                $message = 'Etiqueta impresa exitosamente';
            } else {
                $digitos = strlen(request()->partNumber);

                if ($digitos < 8) {
                    $bclon = 5;
                    $by = 10;
                } else
                if ($digitos >= 8 && $digitos < 11) {
                    $bclon = 4;
                    $by = 10;
                } else if ($digitos > 10 && $digitos < 20) {
                    $bclon = 3;
                    $by = 80;
                }
                $conn = fsockopen($printer[0]->printer, 9100, $errno, $errstr);
                /*  if (Str::contains(request()->getClientIp(), '80') || Str::contains(request()->getClientIp(), '81') || Str::contains(request()->getClientIp(), '82')) {
                    $data = '^XA
                        ^FO85,37^A0,37^FD' . request()->family . '^FS
                        ^FO385,37^A0,37^FD' . request()->partNumber . '^FS
                        ^BY' . $bclon . ',2,65
                        ^FO65,80^BCN,80,N,N^FD' . request()->partNumber . '^FS
                        ^FO200,165^A0,22^FDMade in China^FS
                    ^XZ
                    ';
                } else { */
                $data = ' 
                    ^XA
                    ^FO50,80^A0,30^FD' . request()->family  . '^FS
                    ^FO400,80^A0,30^FD' . request()->partNumber . '^FS
                    ^BY' . $bclon . ',2,65
                    ^FO' . $by . ',110^BCN,120,N,N^FD' . request()->partNumber . '^FS
                    ^FO200,240^A0,32^FDMade in China^FS
                    ^XZ';
                /*     } */
                fputs($conn, $data, strlen($data));
                fclose($conn);
                $returnValue = 1;
                $message = 'Etiqueta impresa exitosamente';
            }




            $log = new CaliperLog;
            $log->part_number = request()->partNumber;
            $log->family = request()->family;
            $log->created_at = Carbon::now();
            $log->created_by = auth()->user()->id;
            $log->save();
        }


        return response()->json([
            'message' => $message,
            'returnValue' => $returnValue,
        ]);
    }

    public function storeFamily(Request $request)
    {

        $input = $request->input;
        $select = $request->select;

        $caliper = Caliper::where('part_number', $request->part_number)->first();

        if ($input) {
            $caliper->family = $input;
        } else if ($select) {
            $caliper->family = $select['family'];
        }
        $caliper->save();
        return $caliper;
        return;
    }
    public function getFamilies()
    {
        return Caliper::select('family')->where('family', '!=', null)->distinct()->get()->toArray();
    }
    public function calipers()
    {
        $print = new PrintLabelController;
        return view('calipers.index', [
            "computer" => $print->getPrinter()
        ]);
    }

    public function getCaliper($partnumber)
    {
        $caliper = Caliper::where('part_number', $partnumber)->first();

        if ($caliper) {
            $state = true;
            $data = $caliper;
        } else {
            $state = false;
            $data = null;
        }

        return ['state' => $state, 'data' => $data];
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Caliper  $caliper
     * @return \Illuminate\Http\Response
     */
    public function show(Caliper $caliper)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Caliper  $caliper
     * @return \Illuminate\Http\Response
     */
    public function edit(Caliper $caliper)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Caliper  $caliper
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Caliper $caliper)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Caliper  $caliper
     * @return \Illuminate\Http\Response
     */
    public function destroy(Caliper $caliper)
    {
        //
    }
}
