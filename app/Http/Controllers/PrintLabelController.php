<?php

namespace App\Http\Controllers;

use App\Models\Printer;
use App\Models\PrintLabelHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrintLabelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('reman_labels.print', [
            "computer" => Printer::whereRelation('computer', 'computer_ip', request()->getClientIp())->get()
        ]);
    }


    public function validateUPC()
    {
        if (!request()->has('upc'))
            return;


        $upcNumber = request()->upc;

        return response()->json([
            "upc" => DB::select("CALL `SelectUPCPartNumber`('$upcNumber')"),
        ]);
    }

    public function printLabel()
    {
        try {
            $printer = Printer::whereRelation('computer', 'computer_ip', request()->getClientIp())->get();
            $message = '';
            $returnValue = 0;
            if (isset($printer[0])) {
                $conn = fsockopen($printer[0]->printer, 9100, $errno, $errstr);
                $data = ' 
^XA
^FO155,57^A0,57^FDPart #:' . request()->partNumber . '^FS
^BY3,2,65
^FO50,110^BCN,120,N,N^FD' . request()->upc . '^FS
^FO10,245^A0,32^FD' . request()->location . '^FS
^FO350,245^A0,32^FD Made in china^FS
^XZ
';
                $fput = fputs($conn, $data, strlen($data));
                fclose($conn);
                $returnValue = 1;
                $message = 'Etiqueta impresa exitosamente';
            }
        } catch (\Throwable $th) {
            $message = $th->getMessage();
        }

        if ($returnValue == 1) {
            PrintLabelHistory::create([
                "user_id" => request()->user()->id,
                "printer_from" => request()->getClientIp(),
                "upc_scanned" => request()->upc,
                "part_number" => request()->partNumber,
                "location" =>  request()->location
            ]);
        }

        return response()->json([
            'message' => $message,
            'returnValue' => $returnValue,
        ]);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
