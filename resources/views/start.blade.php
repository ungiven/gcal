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
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans&display=swap');
        
        #main {
            width: 30vw;
            margin: 0 auto 0 auto;
        }

        h1, h2 {
            font-family: 'Raleway';
        }
    
        h2 {
            border-bottom: 1px solid #bbb;
        }

        h3 {
            font-family: 'Open Sans';
            font-size: 16px;
            text-transform: capitalize;

        }

        h1 {
            border-bottom: 2px solid #bbb;
            padding-bottom: 5px;
        }

        p.time {
            font-family: 'Open Sans', 'Tahoma', 'sans-serif';
            font-size: 11px;
        }

        
    </style>
</head>
<body>
    <div><h1>Calendar</h1></div>
    <div id="main">
        <div id="app">

            <calendar :title="'{{$title}}'" :items="{{$items}}" :csrf="'{{csrf_token()}}'"></calendar>
        </div>
    <div id="form">
        <h2>Add new event</h2>
        <form action="/add" method="POST">
            @csrf
            <input type="date" name="date" id="date" />
            <input type="time" name="time" id="time" />
            <input type="time" name="end" id="end" />
            <input type="text" name="name" id ="name" placeholder="Event name" />
            <input type="submit" id="submiButton" value="Add event" />
        </form>
    </div>
    </div>

</body>
</html>