<?php

namespace App\Http\Controllers\empresas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class empresasController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return view('empresas.empresas');
    }
}
