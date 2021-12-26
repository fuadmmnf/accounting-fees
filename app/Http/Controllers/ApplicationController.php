<?php

namespace App\Http\Controllers;

use App\Application;
use App\ApplicationFee;
use App\Field;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApplicationController extends Controller {
	public function index(Request $request) {
        $selectedDate = Carbon::createFromFormat('d/m/Y', $request->query('date'));
        $categoryId = $request->query('category_id');
        $selectedDayApplications = Application::whereDate('date', $selectedDate);
        if($categoryId != 0){
            $selectedDayApplications->where('category_id', $categoryId);
        }
        $selectedDayApplications = $selectedDayApplications->get();
        $selectedDayApplications->load('category', 'applicationfees', 'applicationfees.field');
        return response()->json($selectedDayApplications);
	}

	public function store(Request $request) {
        $validated = $request->validate([
            'category_id' => 'required|numeric',
            'date' => 'required',
            'amount' => 'required|numeric',
            'fields' => 'required|array' // field_id, optional_field_name, unit, amount
        ]);

        \DB::beginTransaction();
        try {
            $application = Application::create([
                'category_id' => $validated['category_id'],
                'date' => Carbon::createFromFormat('d/m/Y', $validated['date']),
                'amount' => $validated['amount']
            ]);

            foreach ($validated['fields'] as $fieldInfo) {
                $field = (is_numeric($fieldInfo['field_id'])) ? Field::find($fieldInfo['field_id']) : null;
                ApplicationFee::create([
                    'application_id' => $application->id,
                    'field_id' => ($field ? $field->id : null),
                    'optional_field_name' => ($field ? '' : $fieldInfo['optional_field_name']),
                    'unit' => $fieldInfo['unit'],
                    'amount' => $fieldInfo['amount']
                ]);
            }
        } catch (\Exception $exception){
            \DB::rollBack();
            return response()->json($exception->getMessage(), 500);
        }
        \DB::commit();
        return response()->json($application, 201);
	}

	public function show($id) {
		//
	}

	public function update(Request $request, $id) {
		//
	}

	public function destroy($id) {
		$application = Application::find($id);
        \DB::beginTransaction();
        try {
		$application->applicationfees()->delete();
		$application->delete();
        } catch (\Exception $exception){
            \DB::rollBack();
            return response()->json($exception->getMessage(), 500);
        }
        \DB::commit();
		return response()->noContent();
	}
}
