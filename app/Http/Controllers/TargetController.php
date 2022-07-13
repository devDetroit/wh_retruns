<?php

namespace App\Http\Controllers;

use App\Models\Target;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class TargetController extends Controller
{
    protected $lines = [
        "Station 1",
        "Station 2",
        "Station 3",
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //dd(Target::whereDate('created_at', date('Y-m-d'))->orderBy('production_day', 'desc')->limit(10)->get());
        return view('Target.index', [
            "lines" => $this->lines,
            "recordGoals" => Target::whereDate('created_at', date('Y-m-d'))->orderBy('production_day', 'desc')->limit(10)->get()
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
        request()->validate([
            "goal" => "required|numeric"
        ]);

        Target::updateOrCreate(
            ["production_day" => date('Y-m-d'), "station" => $request->station],
            ["goal" => $request->input('goal'), "warehouse" => "jrz", "total_printed" => isset($request->total_printed) ? $request->total_printed : 0, "created_by" => $request->user()->id]
        );

        return back()->with('success', 'Registro insertado');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Target  $target
     * @return \Illuminate\Http\Response
     */
    public function show(Target $target)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Target  $target
     * @return \Illuminate\Http\Response
     */
    public function edit(Target $target)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Target  $target
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Target $target)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Target  $target
     * @return \Illuminate\Http\Response
     */
    public function destroy(Target $target)
    {
        //
    }
}
