<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="{{ asset('js/app.js') }}" defer></script>
    <title>Document</title>
    <style type="text/css">
        #app {
            width: 30vw;
            margin: 0 auto 0 auto;
        }
    </style>
</head>
<body>
    <div id="app">
        <div id="main">
    <calendar :title="'{{$title}}'" :items="{{$items}}"></calendar>
    </div>
    <div id="form">
        <h2>Form goes here</h2>
        <form action="/add" method="POST">
            @csrf
            <input type="date" name="date" id="a" />
            <input type="time" name="time" id="b" />
            <input type="text" name="name" placeholder="Event name" />
            <input type="submit" id="submiButton" value="Add event" />
        </form>
    </div>
    </div>

<pre>{{var_dump($items)}}</pre>
</body>
</html>