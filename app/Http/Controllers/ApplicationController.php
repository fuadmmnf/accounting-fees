<?php

namespace App\Http\Controllers;

use App\Application;
use App\ApplicationFee;
use App\Field;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApplicationController extends Controller {
	public function index(Request $request) {
        $selectedDate = Carbon::parse($request->query('selected_date'));
        $categoryId = $request->query('category_id');
        $selectedDayApplications = Application::whereDate('date', $selectedDate);
        if($categoryId != 0){
            $selectedDayApplications->where('category_id', $categoryId);
        }
        $selectedDayApplications = $selectedDayApplications->get();
        $selectedDayApplications->load('applicationfees', 'applicationfees.field');
        return response()->json($selectedDayApplications);
	}

	public function store(Request $request) {
        $validated = $request->validate([
            'category_id' => 'required|numeric',
            'date' => 'required',
            'amount' => 'required|numeric',
            'fields' => 'required|array' // field_id, optional_field_name, unit, amount
        ]);

        $application = Application::create([
            'category_id' => $validated['category_id'],
            'date' => Carbon::parse($validated['date']),
            'amount' => $validated['amount']
        ]);

        foreach ($validated['fields'] as $fieldInfo){
            $field = (is_numeric($fieldInfo['field_id']))? Field::find($fieldInfo['field_id']): null;
            ApplicationFee::create([
                'application_id' => $application->id,
                'field_id' => ($field? $field->id: null),
                'optional_field_name' => ($field? '': $fieldInfo['optional_field_name']),
                'unit' => ($field? $field->unit: $fieldInfo['unit']),
                'amount' => ($field? $field->amount: $fieldInfo['amount'])
            ]);
        }

        return response()->json($application, 201);
	}

	public function show($id) {
		//
	}

	public function update(Request $request, $id) {
		//
	}

	public function destroy($id) {
		Application::destroy($id);
		return response()->noContent();
	}
}
