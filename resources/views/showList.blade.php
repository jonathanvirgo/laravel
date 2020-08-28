<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Font</title>
    <link href="{{ URL::asset('css/bootstrap.min.css') }}" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-sm">
            <h4 class="center">List Font</h4>
        </div>
        <div class="col-sm">
            <a class="btn btn-light" href="{{route('/')}}">Upload</a>
        </div>
    </div>
    @if(count($results) >0)
        @foreach($results as $font)
            <div class="row">
                <div class="col-sm">
                    <div class="name-font-{{$font->id}}">{{$font->name}}</div>
                    <input type="text" value="{{$font->name}}" class="input-name-font input-name-font-{{$font->id}}">
                </div>
                <div class="col-sm">
                    <svg height="30px">
                        {!!html_entity_decode($font->path)!!}
                    </svg>
                </div>

                <div class="col-sm">
                    <button type="button" class="btn btn-primary" onclick="showEdit({{$font->id}})">Edit</button>
                    <button type="button" class="btn btn-primary" onclick="save({{$font->id}})">Save</button>
                    <a type="button" class="btn btn-warning" href="{{route('deleteFont', $font->id)}}" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                </div>
            </div>
        @endforeach
    @endif
    <div class="alert alert-success w-25 fixed-bottom" role="alert" style="left: 74%;position: fixed">
        Success
    </div>
    <div class="alert alert-warning w-25 fixed-bottom" role="alert" style="left: 74%;position: fixed">
        Error
    </div>
</div>
<script type="text/javascript" src="{{ URL::asset('js/jquery-3.5.1.min.js') }}"></script>
<script>
    $('.alert-success').hide();
    $('.alert-warning').hide();
    $('.input-name-font').hide();
    function showEdit(id){
        $('.name-font-' + id).hide();
        $('.input-name-font-' + id).show();
    }
    function save(id){
        var value = $('.input-name-font-' + id).val();
        $('.input-name-font-' + id).hide();
        $('.name-font-' + id).text(value);
        $('.name-font-' + id).show();
        $.ajax('{{url("/editName")}}', {
            type: 'POST',
            data:{
                id:id,
                name:value,
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
