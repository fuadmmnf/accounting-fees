<?php

namespace App\Http\Controllers;

use App\Application;
use App\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use ZanySoft\LaravelPDF\Facades\PDF;

class PdfController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type');
        $date = Carbon::createFromFormat('d/m/Y', $request->query('date'));
        $category = $request->query('category_id');
        $fields = [];
        $localTax = [0, 0]; // 0=> union, 1=> corporation
        $count = 0;
        $selectedDayApplications = Application::query();
        if ($type == 0) {
            $selectedDayApplications->whereDate('date', $date);
        } elseif ($type == 1) {
            $selectedDayApplications->whereMonth('date', $date)->whereYear('date', $date);
        } else {
            $selectedDayApplications->whereYear('date', $date);
        }
        if ($category != 0) {
            $selectedDayApplications->where('category_id', $category);
        }

        $selectedDayApplications->with('category')->with('applicationfees')->with('applicationfees.field')->chunk(200, function ($applications) use (&$fields, &$count, &$localTax) {
            $count += count($applications);
            foreach ($applications as $application) {
                foreach ($application->applicationfees as $applicationfee) {
                    $fieldname = ($applicationfee->field_id ? $applicationfee->field->name : $applicationfee->optional_field_name);
                    if (!isset($fields[$fieldname])) $fields[$fieldname] = 0;
                    $amount = ($applicationfee->unit == 0 ? ($application->amount * $applicationfee->amount / 100.0) : $applicationfee->amount);
                    $fields[$fieldname] += $amount;
                    if ($fieldname == 'স্থানীয় সরকার কর') {
                        $localTax[$application->type == 'union' ? 0 : 1] += $amount;
                    }
                }
            };
        });

        $fields = ['সর্বমোট রেজিস্ট্রেশন ফি' => $fields['রেজিস্ট্রেশন ফি'] + $fields['ই ফিস'] + $fields['এন ফিস']] + $fields;
        $igr_funds = [$localTax[0] * 3.5 / 100.0, $localTax[1] * 3.5 / 100.0];
        $fields = $fields + ['স্থানীয় - আই জি আর' => $igr_funds[0] + $igr_funds[1]];
        $fields = $fields + ['স্থানীয় - জেলা' => ($fields['স্থানীয় সরকার কর'] - ($igr_funds[0] + $igr_funds[1])) / 3.0];
        $fields = $fields + ['স্থানীয় - উপজেলা' => ($localTax[0] - $igr_funds[0]) / 3.0];
        $fields = $fields + ['স্থানীয় - ইউনিয়ন' => ($localTax[0] - $igr_funds[0]) / 3.0];
        $fields = $fields + ['স্থানীয় - পৌরসভা' => ($localTax[1] - $igr_funds[1]) * (2.0 / 3.0)];

        $pdf = PDF::make('document.pdf');
        $fontdata = array(
            'kalpurush' => [
                'R' => 'kalpurush.ttf',      // regular font
                'B' => 'kalpurush.ttf',     // optional: bold font
                'I' => 'kalpurush.ttf',     // optional: italic font
                'BI' => 'kalpurush.ttf',      // optional: bold-italic font
                'useOTL' => 0xFF,    // required for complicated langs like Persian, Arabic and Chinese
                'useKashida' => 75,  // required for complicated langs like Persian, Arabic and Chinese
            ]
            // ...add as many as you want.
        );

        $pdf->addCustomFont($fontdata, true);
        $pdf->loadView(
            ['reports.daily', 'reports.monthly', 'reports.yearly'][$type],
            ['fields' => $fields, 'date' => $date, 'categoryName' => ($category == 0 ? 'সকল ধরন' : Category::find($category)->name), 'count' => $count]
        );

        $fileName = 'Report' . substr(md5(random_bytes(10)), 7) . '.pdf';
        return $pdf->stream($fileName);
    }
}
