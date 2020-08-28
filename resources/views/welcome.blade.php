<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Upload Font</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link href="{{ URL::asset('css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('css/style.css') }}" rel="stylesheet">
    </head>
    <body>
        <div class="position-ref container-fluid">
            <div class="content">
                <div class="title m-b-md center">
                    Upload Font
                </div>
                <form action="{{route('postFile')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="font">
                    <input type="submit">
                </form>
                <input type="button" onclick="window.location='{{ URL::route('showData')}}'" value="Danh sách font" style="position: absolute;top: 96px;left: 420px;">
                <div style="display: none" class="div-name flex-display">
                    <div class="name-font flex-item1"></div>
                    <div>
                        <input type="button" value="Save" class="bt-save" onclick="onSave()">
                    </div>
                    <input type="checkbox" class="check-all" onclick="checkAll()">
                </div>
                <div class="list-font"></div>
                <div class="alert alert-success w-25 fixed-bottom" role="alert" style="left: 74%;position: fixed">
                    Success
                </div>
                <div class="alert alert-warning w-25 fixed-bottom" role="alert" style="left: 74%;position: fixed">
                    Error
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
        <script type="text/javascript" src="{{ URL::asset('js/jquery-3.5.1.min.js') }}"></script>
        <script type="text/javascript" src="{{ URL::asset('js/bootstrap.min.js') }}"></script>
        <script>
            $('.alert-success').hide();
            $('.alert-warning').hide();
            $('.list-data').hide();
            var listFont = [];
            var name = '';
            var pathRegular = '';
            // var id = Date.now() + (Math.random()*100).toFixed();
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
                            let fontObj = {id: timestamp, link: element.textContent, path: path, name: nameFont};
                            listFont.push(fontObj);
                            makeTable(fontObj, listFont.length);
                            // console.log("nameFont",nameFont, nameFont.includes('Regular'));
                            if(nameFont.includes('Regular')){
                                pathRegular = path;
                            }
                        });
                    }
                });
                document.querySelector('.check-all').checked = true;
            }
            async function getPath(link, name){
                // console.log("link", 'storage' + link.slice(6));
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
                var listFontActive = listFont.filter(s => s.active == true);
                $.ajax('{{url("/saveData")}}', {
                    type: 'POST',
                    data:{
                        name:name,
                        listFont: listFontActive,
                        pathRegular: pathRegular == '' ? listFontActive[listFontActive.length - 1].path: pathRegular,
                        _token: '{{csrf_token()}}'
                    },
                    success:function(response) {
                        $(".alert-success").fadeTo(2000, 500).slideUp(500, function() {
                            $(".alert-success").slideUp(500);
                        });
                    },
                    error:function (error){
                        $(".alert-warning").fadeTo(2000, 500).slideUp(500, function() {
                            $(".alert-warning").slideUp(500);
                        });
                    }
                })
            }
        </script>
    </body>

</html>
