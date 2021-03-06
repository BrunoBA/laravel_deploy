<?php

namespace App\Http\Controllers;

use App\Release;
use Illuminate\Http\Request;
use mikehaertl\shellcommand\Command;

class ReleaseController extends Controller
{
    public function index()
    {
        return view('releases.index')->withReleases(Release::get());
    }

    public function store(Request $request)
    {
        $name = $request->all()['pusher']['name'];
        $content = var_export($request->all(), true);

        Release::create(['payload' => $content, 'name' => $name]);
    }
}
