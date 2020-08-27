<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Process</title>
</head>
<body>
<div class="name"></div>
    <ul style="visibility: hidden">
        @if(session()->has('listFile'))
        @foreach(session()->get('listFile') as $item)
        <li class="item">{{$item}}</li>
        @endforeach
        @endif
    </ul>
</body>
<script type="text/javascript" src="{{ URL::asset('js/opentype.min.js') }}"></script>
<script>
    var file = [];
    if(document.querySelectorAll('.item').length > 0) {
        document.querySelectorAll('.item').forEach(function (element) {
            if (!element.textContent.includes('README.txt') && element.textContent.substr(-4,4) == '.ttf') {
                console.log("item", element.textContent.split('/')[2]);
                var path = getPath(element.textContent);
            }
        });
    }
    async function getPath(link){
        console.log("link", 'storage' + link.slice(6));
        var font = await opentype.load('storage' + link.slice(6));
        var path = font.getPath("Roboto", 0, 14, 14);
        console.log("path", path.toSVG());
        return path.toSVG();
    }

</script>
</html>
