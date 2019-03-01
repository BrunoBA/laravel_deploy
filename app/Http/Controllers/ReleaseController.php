<?php

namespace App\Http\Controllers;

use App\Release;
use Illuminate\Http\Request;

class ReleaseController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());

        $content = var_export($request->all(), true);
        Release::create(['payload' => $content, 'name' => 'Bruno']);
    }
}
