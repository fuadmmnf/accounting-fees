@extends('reports.reportlayout')

@section('title')
    PDF | Yearly Report
@endsection

@section('report_title')
    Yearly Report - {{date('Y', strtotime($date))}}
@endsection
