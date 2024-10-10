<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Services\UserService;

class SearchController extends Controller
{
    public function index()
    {
        return view('search');
    }

    public function check(SearchRequest $request)
    {
        $user = UserService::findByNid($request->nid);

        return view('status', compact('user'));
    }
}
