<?php

namespace App\Http\Controllers;

use App\Models\Printer;
use App\Models\PrintLabelHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
            "computer" => $this->getPrinter()
        ]);
    }


    public function validateUPC()
    {
        if (!request()->has('upc')  && !request()->has('warehouse'))
            return;

        $table = request()->warehouse == 'jrz' ? 'upclocations' : 'upcdetroitlocations';

        $upc = DB::table($table)
            ->where([
                ['UPC', request()->upc],
                ['UPC', '>', 0],
            ])
            ->limit(1)
            ->get();

        $partNumber = DB::table($table)
            ->where('Item', request()->upc)
            ->limit(1)
            ->get();


        return response()->json([
            "upc" => isset($upc[0]->Item) ? $upc : $partNumber,
        ]);
    }

    public function getPrinter()
    {
        return Printer::whereRelation('computer', 'computer_ip', request()->getClientIp())->get();
    }

    public function printLabel()
    {
        try {
            $printer = $this->getPrinter();
            $message = '';
            $returnValue = 0;
            $location = isset(request()->location) ? request()->location : '';
            if (isset($printer[0])) {

                $conn = fsockopen($printer[0]->printer, 9100, $errno, $errstr);
                $data = ' 
^XA
^FO155,57^A0,57^FDPart #:' . request()->partNumber . '^FS
^BY3,2,65
^FO50,110^BCN,120,N,N^FD' . request()->upc . '^FS
^FO10,245^A0,32^FD' . $location . '^FS
^XZ
';
                fputs($conn, $data, strlen($data));
                fclose($conn);
                $returnValue = 1;
                $message = 'Etiqueta impresa exitosamente';

                $this->saveHistory();
                $this->updateCounter();
            }
        } catch (\Throwable $th) {
            $message = $th->getMessage();
        }

        return response()->json([
            'message' => $message,
            'returnValue' => $returnValue,
        ]);
    }
    private  function saveHistory()
    {
        PrintLabelHistory::create([
            "user_id" => request()->user()->id,
            "printer_from" => request()->getClientIp(),
            "upc_scanned" => request()->upc,
            "part_number" => request()->partNumber,
            "location" =>  isset(request()->location) ? request()->location : ''
        ]);
    }
    private function updateCounter()
    {
        DB::table('targets')->where('station', Str::of(request()->user()->complete_name)->ucfirst())->increment('total_printed');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('reman_labels.add-upcnumbers');
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
