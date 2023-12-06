<?php

namespace App\Http\Controllers;

use App\Models\Component;
use App\Models\PartType;
use Illuminate\Http\Request;

class PartTypesController extends Controller
{
    function getTypes()
    {
        return PartType::all();
    }

    public function getComponentsOfType($id)
    {
        if ($id == 1) {
            return Component::where('belongs_to', $id)->get();
        } else {
            $array = [];
            $components = Component::where('belongs_to', $id)->get();
            $data1 = [
                'component' => null,
                'quantity' => 0,
                'components' => $components,
                'disableComponent' => false,
                'disableQuantity' => true,
                'min' => 0,
                'max' => 1
            ];

            $bd1 = [
                'component' => Component::where('part_num', 'BD-1')->first(),
                'quantity' => 0,
                'components' => $components,
                'disableComponent' => true,
                'disableQuantity' => false,
                'min' => 0,
                'max' => 2
            ];
            $bd2 = [
                'component' => Component::where('part_num', 'BD-2')->first(),
                'quantity' => 0,
                'components' => $components,
                'disableComponent' => true,
                'disableQuantity' => false,
                'min' => 0,
                'max' => 2
            ];
            $nut = [
                'component' => null,
                'quantity' => 1,
                'components' => Component::where('belongs_to', 3)->get(),
                'disableComponent' => false,
                'disableQuantity' => true,
                'min' => 0,
                'max' => 1
            ];

            array_push($array, $data1, $data1, $bd1, $bd2, $nut);

            return $array;
        }
    }
}
