<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Jobs\ScheduleVaccinationDateJob;
use App\Models\User;
use App\Models\VaccineCenter;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        $vaccineCenters = VaccineCenter::all(['id', 'name'])->pluck('name', 'id');

        return view('registration', compact('vaccineCenters'));
    }

    public function store(RegisterRequest $request)
    {
        $user = User::create($request->validated());
        ScheduleVaccinationDateJob::dispatch($user);

        return redirect()->back()->with('success', 'Registration successfull!');
    }
}
