<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="{{ asset('js/app.js') }}" defer></script>
    <title>Document</title>
    <style type="text/css">
        @import url('https://fonts.googleapis.com/css2?family=Raleway&display=swap');
        
        #app {
            width: 30vw;
            margin: 0 auto 0 auto;
        }

        h3 {
            font-family: 'Raleway';
            font-weight: 100;
        }

        
    </style>
</head>
<body>
    <div id="app">
        <div id="main">
        <calendar :title="'{{$title}}'" :items="{{$items}}" :csrf="'{{csrf_token()}}'"></calendar>
    </div>
    <div id="form">
        <h2>Form goes here</h2>
        <form action="/add" method="POST">
            @csrf
            <label for="date">Date</label>
            <input type="date" name="date" id="date" />
            <input type="time" name="time" id="time" />
            <input type="time" name="end" id="end" />
            <input type="text" name="name" id ="name" placeholder="Event name" />
            <input type="submit" id="submiButton" value="Add event" />
        </form>
    </div>
    </div>

<pre>{{var_dump($items)}}</pre>
</body>
</html>