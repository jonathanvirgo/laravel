<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">

            <div class="content">
                <div class="title m-b-md">
                    Laravel Test
                </div>

                <form action="{{route('postFile')}}" method="post" enctype="multipart/form-data">
                    @csrf
{{--                    <input type="hidden" value="part" class="part" name="part">--}}
{{--                    <input type="hidden" value="abc" class="abc" name="abc">--}}
{{--                    <input type="hidden" value="bcd" class="bcd" name="bcd">--}}
                    <input type="file" name="font">
                    <input type="submit">
                </form>
            </div>
        </div>
    </body>
    <script src="resources/js/opentype.min.js"></script>
    <script>
        var font = await opentype.load('fonts/Roboto-Black.ttf');
        console.log("font", font);
        // var path = font.getPath("ten font", 0, fontSize, fontSize, options);
        // let result = path.toSVG();
        // $('.part').val(result);
        // $('.abc').val(result);
        // $('.bcd').val(result);
    </script>
</html>
