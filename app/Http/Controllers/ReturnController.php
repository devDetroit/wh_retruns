<?php

namespace App\Http\Controllers;

use App\Models\PartNumber;
use App\Models\Returns;
use App\Models\ReturnStatus;
use App\Models\Status;
use App\Models\Store;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{

    protected $isValidOperation = 1;
    protected $message = '';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('returns.index', [
            "counters" => DB::select('CALL sp_counters()'),
            "totalRecords" => DB::table('returns')->count()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('returns.create', [
            'statuses' => Status::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {

        $attributes = request()->validate([
            'track_number' => ['required', Rule::unique('returns', 'track_number')]
        ]);

        if (isset($attributes['errors'])) {
            $this->isValidOperation = 0;
            $this->message = 'something went wrong';
        }

        try {
            $return = Returns::create(array_merge([
                'returnstatus_id' => 1,
                'created_by' => request()->user()->id,
            ], $attributes));

            $partNumbers = [];


            foreach (request()->all() as $row) {
                if (is_array($row)) {
                    array_push($partNumbers, array_merge($row, [
                        'returns_id' => $return->id
                    ]));
                }
            }

            foreach ($partNumbers as $partNumber) {
                PartNumber::create($partNumber);
            }

            $this->message = 'record successfully inserted';
        } catch (\Throwable $th) {
            $this->isValidOperation = 0;
            $this->message = $th;
        }


        return response()->json([
            'status' => $this->operationDescritpion(),
            'returnValue' => $return->id,
            'message' => $this->message,
            'errors' => isset($attributes['errors']) ? $attributes : []
        ]);
    }


    public function storeFiles()
    {
        for ($i = 0; $i < request()->totalImages; $i++) {
            if (request()->hasFile("images$i")) {
                $file = request()->file("images$i");
                $name = $file->getClientOriginalName();
                request()->file("images$i")->storeAs("public/PartNumbers", request()->return_id . "-" . $name);
            }
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Returns $return)
    {

        return view('returns.show', [
            'return' => $return,
            'partnumbers' => $return->partnumbers()->leftJoin('upcpartnumbers', 'part_numbers.partnumber', '=', 'upcpartnumbers.upc')->select('part_numbers.*', 'upcpartnumbers.item', 'upcpartnumbers.UPC')->get(),
            'return_status' => ReturnStatus::all(),
            'stores' => Store::all(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Returns $return)
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
    public function update(Returns $return)
    {

        request()->validate([
            'order_number' => 'required'
        ]);

        $return->returnstatus_id = request()->returnstatus_id;
        $return->order_number = request()->order_number;
        $return->last_updated_by = request()->user()->id;
        $return->store_id = request()->store;

        $return->save();

        return  redirect('returns')->with('status', 'Record Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Returns $return)
    {

        $return->delete();

        return response()->json([
            'status' => 1,
            'returnValue' => 1,
            'message' => 'Record deleted succesfully',
            'errors' => null
        ]);
    }

    private function operationDescritpion()
    {
        return $this->isValidOperation == 1 ? 'success' : 'error';
    }
}
