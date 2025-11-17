<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Provincia;

class DashbordController extends Controller
{
    public function indexHome()
    {
       $provincias = Provincia::all();

        return view('home',  [
             'provincias' => $provincias,
        ]);
    }
}
