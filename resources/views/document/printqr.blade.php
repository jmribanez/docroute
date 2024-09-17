<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print QR Code</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
        }
    </style>
</head>
<body>
    <h1>Document Routing System</h1>
    <h3>{{$document->title}}</h3>
    {!! QrCode::size(300)->generate(route('document.show',$document->id)) !!}
    <p style="font-family:monospace; font-size:large;">{{route('document.show',$document->id)}}</p>
</body>
</html>