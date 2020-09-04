<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <meta name="description" content="SaveFav helps use remember  web links, domain names, url, web sites. never forget a website or its information again">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name', 'siteFav')  }}</title>

    {{--    scripts--}}
    <script src="{{asset('js/app.js')}}" defer></script>
    <link rel="stylesheet" href="{{asset('css/app.css')}}">

</head>
<body >
        <div class="container" id="app">
            @if($sharedLink)
                <dashboard-component shared="{{$sharedLink}}"></dashboard-component>
            @else
                <dashboard-component></dashboard-component>
            @endif
        </div>
</body>
</html>
