<?php

namespace App\Http\Controllers;

use App\Models\Printer;
use App\Models\PrintLabelHistory;
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

    private function validateRequesterID()
    {
        if (!isset(request()->user()->id)) {
            return response()->route('login');
        }
    }
    public function getPrinter()
    {
        return Printer::whereRelation('computer', 'computer_ip', request()->getClientIp())->get();
    }

    public function printLabel()
    {
        $this->validateRequesterID();

        try {
            $printer = $this->getPrinter();
            $message = '';
            $returnValue = 0;
            $clientIP = request()->ip();
            $location = isset(request()->location) ? request()->location : '';
            $getCurrentDate = date('m/d/y');
            $data  = '';
            $facility = '';
            if (isset($printer[0])) {

                $conn = fsockopen($printer[0]->printer, 9100, $errno, $errstr);
                $pnXPosition = strlen(request()->partNumber) < 6 ? 'FO130' : 'FO85';

                /* 
                if (Str::contains(request()->getClientIp(), '80') || Str::contains(request()->getClientIp(), '81') || Str::contains(request()->getClientIp(), '82')) {
                    $data = '^XA
                        ^FO85,37^A0,37^FDPart #' . request()->partNumber . '^FS
                        ^FO530,37^A0,17^FD' . $getCurrentDate . '^FS
                        ^BY3,2,65
                        ^FO50,80^BCN,80,N,N^FD' . request()->upc . '^FS
                        ^FO30,165^A0,22^FD' . $location . '^FS
                        ^FO420,165^A0,22^FDMade in China^FS
                    ^XZ
                    ';
                } else { */
                if (Str::startsWith($clientIP, '10.10')) {
                    $facility = 'DT';
                } elseif (Str::startsWith($clientIP, '10.0')) {
                    $facility = 'EP';
                } else {
                    $facility = 'RM';
                }
                $data = ' 
                        ^XA
                            ^' . $pnXPosition . ',57^A0,57^FDPart #:' . request()->partNumber . '^FS
                            ^FO530,57^A0,20^FD' . $getCurrentDate . '^FS
                            ^BY3,2,65
                            ^FO50,110^BCN,120,N,N^FD' . request()->upc . '^FS
                            ^FO10,245^A0,32^FD' . $location . '^FS
                            ^FO310,245^A0,32^FDMade in China^FS
                            ^FO520,250^A0,20^FD' . $facility . '^FS
                        ^XZ
                        ';
                /* } */

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
        DB::table('targets')->where([
            ['station', Str::of(request()->user()->complete_name)->ucfirst()],
            ['production_day', date('Y-m-d')]
        ])->increment('total_printed');
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
}
