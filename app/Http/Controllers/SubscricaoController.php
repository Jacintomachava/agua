<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mensalidade;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SubscricaoController extends Controller
{
    public function index()
    {
        $userActual = Auth::user();

        $mensalidades = Mensalidade::where('empresa_id',$userActual->empresa_id)->get();
        // Retorna a view com os dados carregados
        return view('subscricao.index', [
            'mensalidades' => $mensalidades,
        ]);
    }
}
