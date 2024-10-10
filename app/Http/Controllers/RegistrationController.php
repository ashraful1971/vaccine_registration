<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Services\UserService;
use App\Services\VaccineCenterService;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        $vaccineCenters = VaccineCenterService::all();

        return view('registration', compact('vaccineCenters'));
    }

    public function store(RegisterRequest $request)
    {
        UserService::register($request->validated());

        return redirect()->back()->with('success', 'Registration successfull!');
    }
}
