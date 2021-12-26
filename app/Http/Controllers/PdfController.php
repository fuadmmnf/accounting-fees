<?php

namespace App\Http\Controllers;

use App\Application;
use App\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Meneses\LaravelMpdf\Facades\LaravelMpdf as PDF;

class PdfController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type');
        $date = Carbon::createFromFormat('d/m/Y', $request->query('date'));
        $category = $request->query('category_id');
        $fields = [];

        $selectedDayApplications = Application::query();
        if ($type == 0) {
            $selectedDayApplications->whereDate('date', $date);
        } elseif ($type == 1) {
            $selectedDayApplications->whereMonth('date', $date);
        } else {
            $selectedDayApplications->whereYear('date', $date);
        }
        if ($category != 0) {
            $selectedDayApplications->where('category_id', $category);
        }
        $selectedDayApplications->with('category')->with('applicationfees')->with('applicationfees.field')->chunk(200, function ($applications) use (&$fields){
            foreach ($applications as $application) {
                foreach ($application->applicationfees as $applicationfee){
                    $fieldname = ($applicationfee->field_id? $applicationfee->field->name: $applicationfee->optional_field_name);
                    if(!isset($fields[$fieldname])) $fields[$fieldname] = 0;
                    $fields[$fieldname] += ($applicationfee->unit == 0? ($application->amount * $applicationfee->amount/100.0): $applicationfee->amount);
                }
            };
        });

        $fields = ['সর্বমোট রেজিস্ট্রেশন ফি' => $fields['রেজিস্ট্রেশন ফি'] + $fields['ই ফিস'] + $fields['এন ফিস']] + $fields;
        $igr_fund = $fields['স্থানীয় সরকার কর'] * 3.5/100.0;
        $fields = $fields + ['স্থানীয় - আই জি আর' => $igr_fund];
        $fields = $fields + ['স্থানীয় - জেলা' => ($fields['স্থানীয় সরকার কর']-$igr_fund)/3.0];
        $fields = $fields + ['স্থানীয় - উপজেলা' => ($fields['স্থানীয় সরকার কর']-$igr_fund)/3.0];
        $fields = $fields + ['স্থানীয় - ইউনিয়ন' => ($fields['স্থানীয় সরকার কর']-$igr_fund)/3.0];

        $pdf = PDF::loadView(
            ['reports.daily', 'reports.monthly', 'reports.yearly'][$type],
            ['fields' => $fields, 'date' => $date, 'categoryName' => ($category == 0? 'সকল ধরন': Category::find($category)->name),]
        );

        $fileName = 'Report' . substr(md5(random_bytes(10)), 7) . '.pdf';
        return $pdf->stream($fileName);
    }
}
