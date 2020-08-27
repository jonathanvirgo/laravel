<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
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
            .flex-display{
                display: flex;
                flex-direction: row;
            }
            .flex-item1{
                width: 30%;
                font-family: "Roboto";
                color: black;
            }
            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }
            .content{
                padding-left: 20px;
            }
            .center {
                text-align: center;
            }

            .title {
                font-size: 44px;
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
            .name-font{
                color: black;
                font-family: Roboto;
                font-size: 33px;
                margin: 20px;
                padding-left: 120px;
            }
            .bt-save{
                position: relative;
                top: 25px;
            }
            .check-all{
                position: relative;
                top: 30px;
                left: 92px;
            }
        </style>
    </head>
    <body>
        <div class="position-ref full-height">
            <div class="content">
                <div class="title m-b-md center">
                    Upload Font
                </div>
                <form action="{{route('postFile')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="font">
                    <input type="submit">
                </form>
                <div style="display: none" class="div-name flex-display">
                    <div class="name-font flex-item1"></div>
                    <div>
{{--                        <form action="{{route('saveData')}}" method="post">--}}
{{--                            <input type="hidden" class="json-font-array" name="list-font" enctype="multipart/form-data">--}}
                            <input type="button" value="Save" class="bt-save" onclick="onSave()">
{{--                        </form>--}}
                    </div>
                    <input type="checkbox" class="check-all" onclick="checkAll()">
                </div>
                <div class="list-font">
                </div>
                <ul style="visibility: hidden">
                    @if(session()->has('listFile'))
                        @foreach(session()->get('listFile') as $item)
                            <li class="item">{{$item}}</li>
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>
        <script type="text/javascript" src="{{ URL::asset('js/opentype.min.js') }}"></script>
        <script
            src="https://code.jquery.com/jquery-3.5.1.min.js"
            integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
            crossorigin="anonymous"></script>
        <script>
            var listFont = [];
            var name = '';
            var pathRegular = '';
            var id = Date.now() + (Math.random()*100).toFixed();
            if(document.querySelectorAll('.item').length > 0) {
                document.querySelector('.div-name').style.display = 'flex';
                document.querySelectorAll('.item').forEach(function (element) {
                    if (!element.textContent.includes('README.txt') && element.textContent.substr(-4,4) == '.ttf') {
                        name = element.textContent.split('/')[2];
                        let nameFont = element.textContent.split('/')[element.textContent.split('/').length - 1];
                        nameFont = nameFont.substring(0, nameFont.length - 4);
                        document.getElementsByClassName('name-font')[0].innerHTML = name;
                        Promise.resolve(getPath(element.textContent, nameFont)).then(path =>{
                            let timestamp = Date.now() + (Math.random()*100).toFixed();
                            let fontObj = {id: timestamp, link: element.textContent, path: path, name: nameFont, fontFamilyId: id};
                            listFont.push(fontObj);
                            makeTable(fontObj, listFont.length);
                            if(nameFont.includes('Regular')){
                                pathRegular = path;
                            }else{
                                pathRegular = path;
                            }
                        });
                    }
                });
                document.querySelector('.check-all').checked = true;
            }
            async function getPath(link, name){
                console.log("link", 'storage' + link.slice(6));
                var font = await opentype.load('storage' + link.slice(6));
                var path = font.getPath(name, 0, 14, 14);
                return path.toSVG();
            }
            function makeTable(obj, listFontLength){
                var table, curRow;
                table = document.getElementsByClassName('list-font')[0];
                curRow = document.createElement('div');
                curRow.classList.add('flex-display');
                var curCellName = document.createElement('div');
                curCellName.classList.add('flex-item1');
                curCellName.appendChild( document.createTextNode(obj.name));
                var curCellSvg = document.createElement('div');
                curCellSvg.classList.add('flex-item2');
                var svgElem = document.createElementNS("http://www.w3.org/2000/svg", 'svg');
                svgElem.setAttribute('height', '30px');
                svgElem.innerHTML = obj.path;
                curCellSvg.appendChild(svgElem);
                var curCellEdit = document.createElement('INPUT');
                curCellEdit.setAttribute("type",'checkbox');
                curCellEdit.classList.add('check-box-font');
                curCellEdit.setAttribute('name', obj.id);
                curCellEdit.checked = true;
                obj['active'] = true;
                curCellEdit.onclick = function (){
                    obj.active = curCellEdit.checked;
                }
                curRow.appendChild(curCellName);
                curRow.appendChild(curCellSvg);
                curRow.appendChild(curCellEdit);
                table.appendChild(curRow);
            }
            function checkAll(){
                var checkList = [];
                var checked = true;
                document.querySelectorAll('.check-box-font').forEach(element =>{
                    if(element.checked == false){
                        checkList.push(element);
                    }
                });
                if(checkList.length > 0){
                    checkList.forEach(element =>{
                       element.checked = true;
                       listFont.forEach(font =>{
                           if(font.id == element.name) font.active = true;
                       })
                    });
                    document.querySelector('.check-all').checked = true;
                }else{
                    document.querySelectorAll('.check-box-font').forEach(element =>{
                       element.checked = false;
                    });
                    listFont.forEach(font =>{
                       font.active = false;
                    });
                }
            }
            function onSave(){
                $.ajax('/saveData', {
                    type: 'POST',
                    data:{
                        id:id,
                        name:name,
                        listFont: listFont.filter(s => s.active == true),
                        pathRegular: pathRegular,
                        _token: '{{csrf_token()}}'
                    },
                    success:function(response) {
                        console.log(response);
                    },
                    error:function (error){
                        console.log(error);
                    }
                })
            }
        </script>
    </body>

</html>
