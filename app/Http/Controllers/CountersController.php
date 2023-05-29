<?php

namespace App\Http\Controllers;

use App\Models\Target;

class CountersController extends Controller
{
    /* protected $groupLines = array(
        "jrz" => [
            "line 1" => [
                "Station 1" => [
                    "id" => 1,
                ],
                "Station 2" => [
                    "id" => 2,
                ]
            ]
        ]
    ); */
    
    protected $groupLines = array(
        "jrz" => [
            "line 1" => [
                "Station 1" => [
                    "id" => 21,
                ],               
                ],
                 "line 2" => [
                "Station 2" => [
                    "id" => 22,
                ]
                ],
                "line 3" => [
                    "Station 3" => [
                        "id" => 23,
                    ]
                    ],
        ]
    ); 

    public function index()
    {
        return view('reman_labels.counter');
    }

    public function counters()
    {
        $currentDate = date("Y-m-d");

        $filterUsers = [
            "stations" => [],
            "users" => [],
        ];

        $filters = [
            ['created_at', '>=', "$currentDate 00:00:01"],
            ['created_at', '<=', "$currentDate 23:59:59"],

        ];
        foreach ($this->groupLines['jrz'][request()->line] as $key => $value) {
            array_push($filterUsers['users'], $this->groupLines['jrz'][request()->line][$key]['id']);
            array_push($filterUsers['stations'], $key);
        }


        return response()->json([
            "target" => Target::where([
                ['warehouse', request()->warehouse],
                ['production_day', $currentDate]
            ])
                ->whereIn('station', $filterUsers['stations'])
                ->get(),
            "info" => isset($this->groupLines[request()->warehouse]) ?  $this->groupLines[request()->warehouse][request()->line] : []
        ]);
    }
}
