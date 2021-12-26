<html>
<head>
    <title>@yield('title')</title>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>

        body {
            font-family: 'kalpurush', 'adorsholipi', sans-serif;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            padding: 5px;
            font-family: 'kalpurush', 'adorsholipi', sans-serif;
            font-size: 14px;
        }

        th {
            font-size: 18px;
            font-weight: bold;
        }

        .bordertable td, th {
            border: 1px solid #A8A8A8;
        }

        tr.border_bottom td {
            border-bottom: 1.5px solid black;
        }


        .storeWaterMark {
            text-align: center;
            font-size: 20px;
            color: #b8cee3;
            opacity: 0.01 !important;
        }

        @page {
            header: page-header;
            footer: page-footer;
        {{--background: url({{ public_path('images/logo.png') }});--}}
        {{--:0.7;--}}
        {{--background-size: cover;--}}
        {{--background-repeat: no-repeat;--}}
        {{--background-position: center center;--}}


        }

    </style>
</head>
<body>

<main>
    <p align="center" style="line-height: 1.2;">
        {{--    @if($store->proprietor)--}}
        {{--        <small>প্রোঃ {{ $store->proprietor }}</small>--}}
        {{--    @endif--}}
        <span align="center" style="color: #397736; border-bottom: 1px solid #397736;">
      @yield('report_title') | {{$categoryName}}
    </span>
    </p>
{{--    <div>@yield('content')</div>--}}
    <table class="bordertable">
        <tr>
            <th>Field Name</th>
            <th>Amount</th>
        </tr>
        @foreach($fields as $key=>$value)
        <tr>
            <td>{{$key}}</td>
            <td>{{$value}} tk</td>
        </tr>
        @endforeach
    </table>
</main>

<htmlpageheader name="page-header" name="page-header">

</htmlpageheader>
<htmlpagefooter name="page-footer" style="margin-top: 5px">
    {{--    <div class="storeWaterMark" style="opacity: 0.1;">--}}
    {{--        <big>{{ $store->name }}</big>--}}
    {{--        @if($store->slogan)--}}
    {{--            <br/>** {{ $store->slogan }} **--}}
    {{--        @endif--}}
    {{--    </div>--}}
</htmlpagefooter>
</body>
</html>
