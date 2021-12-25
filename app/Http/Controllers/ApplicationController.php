<?php

namespace App\Http\Controllers;

use App\Application;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApplicationController extends Controller {
	public function index() {
        $selectedDate = Carbon::today();
        $selectedDayApplications = Application::whereDate('date', $selectedDate)->get();
        $selectedDayApplications->load('applicationfees', 'applicationfees.field');
        return response()->json($selectedDayApplications);
	}

	public function store(Request $request) {
		//
	}

	public function show($id) {
		//
	}

	public function update(Request $request, $id) {
		//
	}

	public function destroy($id) {
		//
	}
}
