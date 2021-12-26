@extends('reports.reportlayout')

@section('title')
    PDF | Daily Report
@endsection

@section('report_title')
    Daily Report - {{date('F d, Y', strtotime($date))}}
@endsection
