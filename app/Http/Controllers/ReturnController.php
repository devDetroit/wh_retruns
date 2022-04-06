<?php

namespace App\Http\Controllers;

use App\Models\PartNumber;
use App\Models\Returns;
use App\Models\Status;
use Illuminate\Http\Request;


class ReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('returns.index');
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

        $return = Returns::create(array_merge(request()->validate([
            'track_number' => 'required'
        ]), [
            'user_id' => request()->user()->id,
            'lastUpdateBy' => request()->user()->id,
        ]));

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


        return response()->json([
            'operation' => 'success',
            'returnValue' => $return->id,
            'message' => 'record successfully inserted',
        ]);
    }


    public function storeFiles()
    {
        for ($i = 0; $i < request()->totalImages; $i++) {
            if (request()->hasFile("images$i")) {
                $file = request()->file("images$i");
                $name = $file->getClientOriginalName();
                request()->file("images$i")->storeAs("PartNumbers", request()->return_id . "-" . $name);
            }
        }
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
