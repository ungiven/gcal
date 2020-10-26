<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style type="text/css">
        button {
            width: 100%;
            margin-bottom: 5px;
        }

        input {
            width: 99%;
            margin-bottom: 5px;
        }

        button:first-child {
            margin-bottom: 30px;
        }

        #main {
            padding: 10px 5px 0 5px;
            width: 33vw;
            margin: 0 auto 0 auto;
            border: 2px solid black;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <a href="{{$authUrl}}" style="text-align: center;"><h1>Get auth code</h1></a>
    <div id="main">
        <form>
        <input type="text" name="authCode" id="authkey" placeholder="Enter auth code here">
        <input type="submit" />
        </form>
    </div>
</body>
</html>