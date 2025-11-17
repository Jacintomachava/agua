<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Distrito;

class DistritoController extends Controller
{

    public function apiDistritos($provinciaID)
    {

        $distritos = Distrito::where('provincia_id',$provinciaID)->get();

        return response()->json($distritos);
    }
}
