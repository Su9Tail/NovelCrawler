<html>
<head>
    <link href="//cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-lg-12 div md-12 div m-12 div xs 12">
            <h2 class="page-header text-center">
                Novel Crawler
            </h2>
        </div>
    </div>
    <table class="table table-bordered">
        <thead>
        <tr>
            <td><strong>书名</strong></td>
            <td><strong>作者</strong></td>
            <td><strong>原文链接</strong></td>
            <td><strong>简介</strong></td>
        </tr>
        </thead>
        <tbody>
        @foreach($novels as $novel)
            <tr>
                <td><a href="/book/{{$novel->id}}">{{$novel->title}}</a></td>
                <td>{{$novel->author}}</td>
                <td><a href="{{$novel->href}}">{{$novel->href}}</a></td>
                <td>{{$novel->description}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
<script src="//cdn.bootcss.com/jquery/3.1.1/jquery.min.js"></script>
<script src="//cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</html>

