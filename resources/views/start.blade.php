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
            /*width: 30vw;
            margin: 0 auto 0 auto;*/
            display: grid;
            grid-template-columns: 30% auto 30%;
            grid-gap: 10px;

        }

        #footer {
            height: 300px;
        }


        h1, h2 {
            font-family: 'Raleway';
        }

        h1 {
            margin-bottom: 40px;
        }
    
        h2 {
            border-bottom: 1px solid #bbb;
            background-color: rgb(32, 129, 255);
            color: white;
            padding: 2px 0 2px 5px;
            margin: 0;
        }

        h3 a {
            font-family: 'Open Sans';
            font-size: 16px;
            text-transform: capitalize;
            font-weight: normal;
            text-decoration: none;
            color: rgb(32, 129, 255);

        }

        h3 a:hover {
            color: rgb(255, 109, 83);
            /*color: white;
            background-color: rgb(32, 129, 255);*/
        }

        h1 {
            border-bottom: 2px solid #bbb;
            padding-bottom: 5px;
        }

        label {
            font-family: 'Calibri', 'sans-serif';
            text-transform: uppercase;
            font-size: 10px;
            display: block;
            color:rgb(32, 129, 255);
        }

        .form-item input {
            display: block;
        }


        .new-event-form {
            display: grid;
            grid-template-columns: auto auto auto;
            grid-teimplate-rows: auto auto;
        }

        #message {
            color: green;
            font-family: 'open sans', 'sans-serif';

        }

        #message h2 {
            background-color: white;
            color: rgb(87, 156, 87);
        }

        #content h2 {
            margin-bottom: 20px;
        }

        .error {
            color: rgb(255, 109, 83)!important;

        }

        .new-event-form input {
            width: 95%;
            border-width: 0 0 1px 1px;
            border-style: solid;
            border-color: #bbb;
            color: #555;
            font-family: 'Open sans', 'Tahoma', 'sans-serif';
            font-size: 12px;
        }

        .wide {
            /*grid-column: span 2;*/
            align-self: end;
        }

        .wide input {
            height: 22px;
            padding: 0;
            width: 99%;
            background-color: white;
            border: 1px solid rgb(32, 129, 255);
            color:  rgb(32, 129, 255);
            font-size: 14px;
        }

        .wide input:hover {
            background-color: rgb(32, 129, 255);
            color: white;
        }

        
    </style>
</head>
<body>
    <div><h1>Google Calendar API</h1></div>

    <div id="main">
        <div id="message" @if(session('shared_error')) class="error" @endif>
            @if(session('shared_message'))
                @if(session('shared_error'))<h2 class="error">Error</h2>
                @else <h2 class="success">Success</h2>
                @endif
            <p class="message">{{session('shared_message')}}</p>
            @endif
        </div>
        <div id="content">
        <div id="app">

            <calendar :title="'{{$title}}'" :items='@json($items)' :csrf="'{{csrf_token()}}'"></calendar>
        </div>
    <div id="form">
        <h2>New event</h2>
        <form action="/add" method="POST" class="new-event-form">
            @csrf
            
            <div class="form-item">
                <label for="date">Date</label>
                <input type="date" name="date" id="date" required/>
            </div>
            <div class="form-item">
                <label for="time">Start</label>
                <input type="time" name="start" id="start" value="00:00" required/>
            </div>
             <div class="form-item">
                <label for="end">End</label>
                <input type="time" name="end" id="end" value="00:00" required/>
            </div>

            <div class="form-item">
                <label for="name">Event Name</label>
                <input type="text" name="name" id ="name" placeholder="Event name" required/>
            </div>
            <div class="form-item">
                <label for="allday">All day</label>
                <input type="checkbox" name="allday" id="allday">
            </div>
            <div class="wide"><input type="submit" id="submitButton" value="Add event"/></div>
        </form>
    </div>
</div>
    <div id="rest"></div>
</div>
    <div id="footer"></div>
</body>
</html>