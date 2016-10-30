<html>
<head>
    <link href="//cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-lg-12 div md-12 div m-12 div xs 12">
            <h2 class="page-header text-center">
                {{$novel->title}}
            </h2>
        </div>
    </div>
    <table class="table table-bordered">
        @foreach($chapters as $chapter)
            <tr>
                <td><a href="/book/{{$novel->id}}/chapter/{{$chapter->id}}">{{$chapter->chapter}}</a></td>
                <td><a href="{{$chapter->href}}">{{$chapter->href}}</a></td>
            </tr>
        @endforeach
    </table>
</div>
</body>
<script src="//cdn.bootcss.com/jquery/3.1.1/jquery.min.js"></script>
<script src="//cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</html>

