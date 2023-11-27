<?php

namespace App\Http\Controllers;




use App\Models\ComponentType;
use App\Models\Part;
use App\Models\PartRecord;
use App\Models\PartRecordDetails;
use App\Models\Printer;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NewCaliperController extends Controller
{

    function lastRecords()
    {

        return  PartRecord::where('received_date', '!=', null)
            ->orderBy('received_date', 'desc')
            ->get()
            ->take(10);
    }


    public function generateSerialNumber($prefijo)
    {

        $fecha = Carbon::now();

        $last = PartRecord::where('serial_number', 'LIKE', $prefijo . $fecha->format('Ymd') . '%')->latest()->first();

        if ($last) {
            $last = str_replace($prefijo, '',  $last->serial_number);

            $number = $last + 1;
        } else {
            $number = $fecha->format('Ymd') . '001';
        }
        return $number;
    }

    function storePart(Request $request)
    {
        foreach ($request->part['details'] as $detail) {
            $part = PartRecordDetails::find($detail['id']);
            $part->checked_quantity = $detail['quantity'];
            $part->save();
        }
        $prt =  PartRecord::find($request->part['id']);
        $prt->received_date = Carbon::now()->format('Y-m-d H:i:s');
        $prt->save();

        return  $prt;
    }


    function printCaliper(Request $request)
    {
        if (!$request->has("caliper.parttype"))
            return 'No data found';

        $returnValue = 0;
        try {
            $printer = $this->getPrinter();
            $tmpArr = explode(".", request()->getClientIp());
            array_pop($tmpArr);
            $tmpArr = implode(".", $tmpArr);
            if ($tmpArr == '192.168.80' || $tmpArr == '192.168.81' || $tmpArr == '192.168.62') {
                $prefix = 'DAXJZRM';
            }
            $printer = $this->getPrinter();
            $prefijo =  $this->generateSerialNumber($prefix);

            $caliper = PartRecord::create([
                'part' => $request->caliper['id_part'],
                'serial_number' => $prefix . $prefijo,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
            $message = '';
            $returnValue = 0;
            $data = ' 
            ^XA

            ^CF0,30 //Tamaño de letra
            ^FO0,30^GB600,2,3^FS //Primera primera linea h
            ^FO0,70^GB600,2,3^FS     // Primera linea H
            ^FO0,30^GB2,70,3^FS // Primera linea V
            ^FO180,30^GB2,70,3^FS // Segunda linea V
            
            //Headers
            ^FO5,40^FDSerial number^FS  
            ^FO190,40^FD' . $request->caliper['parttype']['part_description'] . '^FS
            ^FO310,40^FDComponents^FS
            ^FO510,40^FDQty^FS
            
            
            //Data
            ^CF0,17
            ^FO300,30^GB2,40,3^FS // Tercera linea V
            ^FO5,80^FD' . $prefix . $prefijo . '^FS // 1,1
            ^FO185,80^FD' . $request->caliper['part_num'] . '^FS // 1,2
            ^FO500,30^GB2,40,3^FS // Cuarta linea V
            ^FO600,30^GB2,40,3^FS // Quinta linea V
            ^FO0,100^GB300,0,3^FS // Segunda linea H
            ^CF0,17//Tamaño de letra

            ^FO17,110^FDFamily^FS  
            ^FO0,60^GB2,70,3^FS // Primera linea V
            ^FO0,130^GB300,0,3^FS // Segunda linea H
            ^FO300,99^GB2,40,3^FS // Tercera linea V
            ^FO125,100^GB2,30,3^FS // Tercera linea V
            ^FO140,110^FD' . $request->caliper['family']['description'] . '^FS  
            ';
            $top = 80;
            $middle = 100;
            $bottom = 60;
            foreach ($request->caliper['components'] as $component) {
                if ($component['quantity'] > 0) {
                    PartRecordDetails::create([
                        'part_record_id' => $caliper->id,
                        'component' => $component['component']['part_num'],
                        'quantity' => $component['quantity']
                    ]);
                    $data = $data .
                        '^FO310,' . $top . '^FD' . $component['component']['part_num'] . '^FS // 1,3
                    ^FO525,' . $top . '^FD' . $component['quantity'] . '^FS //1,4 
                    ^FO300,' . $middle . '^GB300,0,3^FS // Segunda linea H
                    ^FO600,' . $bottom . '^GB2,40,3^FS // Quinta linea V
                    ^FO500,' . $bottom . '^GB2,40,3^FS // Quinta linea V
                    ^FO300,' . $bottom . '^GB2,40,3^FS // Tercera linea V';

                    $top += 30;
                    $middle += 30;
                    $bottom += 30;
                }
            }

            //Barcode
            $data = $data . '^BY2,3,100
            ^FO75,450^BC^FD' . $prefix . $prefijo . '^FS
            ^BY2,3,100
            ^FO25,200^BC^FD' . $request->caliper['part_num'] . '^FS
            ^XZ
                        ';
            $conn = fsockopen($printer[0]->printer, 9100, $errno, $errstr);
            fputs($conn, $data, strlen($data));
            fclose($conn);
            $returnValue = 1;
            $message = 'Etiqueta impresa exitosamente';
        } catch (\Throwable $th) {
            $message = $th->getMessage();
        }

        return response()->json([
            'message' => $message,
            'returnValue' => $returnValue,
        ]);
    }

    public function getPrinter()
    {
        return Printer::whereRelation('computer', 'computer_ip', request()->getClientIp())->get();
    }
    public function index()
    {
        $print = new PrintLabelController;
        return view('newcaliper.index', [
            "computer" => $print->getPrinter()
        ]);
    }
    public function indexChecked()
    {
        $print = new PrintLabelController;
        return view('newcaliper.checked.checked', [
            "computer" => $print->getPrinter()
        ]);
    }



    function findPart($id)
    {
        $tmpArr = explode(".", request()->getClientIp());
        array_pop($tmpArr);
        $tmpArr = implode(".", $tmpArr);
        if ($tmpArr == '192.168.80' || $tmpArr == '192.168.81' || $tmpArr == '192.168.82' || '192.168.62') {
            $prefix = 'DAXJZRM';
        }
        $prefijo =  $this->generateSerialNumber($prefix);
        $caliper = Part::with('parttype', 'family', 'components.part', 'components.component', 'components.component.type')->where('part_num', $id)->first();
        if ($caliper) {
            return ['state' => true, 'caliper' => $caliper, 'serialnumber' => $prefix . $prefijo];
        } else {
            return ['state' => false, 'caliper' => null];
        }
    }

    function findPartChecked($id)
    {
        $part = PartRecord::where('serial_number', $id);
        if (!$part->exists()) {
            return ['state' => 0, 'message' => 'Part not exists'];
        }
        if ($part->where('received_date', '!=', null)->exists()) {
            return ['state' => 1, 'message' => 'Part already been received'];
        } else {
            return ['state' => 2, 'message' => 'Part ready to check', 'part' => PartRecord::where('serial_number', $id)->with('details.part', 'details.component.type')->first()];
        }
    }



    function indexAdd()
    {
        $typecomponent = ComponentType::with('components')->get();
        return view('newcaliper.addCaliper', ['components' => $typecomponent]);
    }

    function component($id)
    {
        $typecomponent = ComponentType::with('components')->get();
        $caliper = Part::with('parttype', 'family', 'components.part', 'components.component', 'components.component.type')->where('part_num', $id)->first();
        if ($caliper) {
            return ['state' => true, 'components' => $typecomponent];
        } else {
            return ['state' => false, 'components' => $typecomponent];
        }
    }
}
