@extends('reports.reportlayout')

@section('title')
    PDF | Monthly Report
@endsection

@section('report_title')
    Monthly Report - {{date('F, Y', strtotime($date))}}
@endsection
