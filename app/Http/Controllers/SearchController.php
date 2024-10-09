<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Models\User;

class SearchController extends Controller
{
    public function index()
    {
        return view('search');
    }

    public function check(SearchRequest $request)
    {
        $user = User::where('nid', $request->nid)->first();

        return view('status', compact('user'));
    }
}
