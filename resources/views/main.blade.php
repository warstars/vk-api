<html>
<head>
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel</title>
    {{--<link href='//fonts.googleapis.com/css?family=Lato:100' rel='stylesheet' type='text/css'>--}}
    <script src="js/app.js"></script>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            color: #000000;
            display: table;
            font-weight: 700;
            font-family: 'Lato';
        }
    </style>
</head>
<body>
{{--<div><a href="https://oauth.vk.com/authorize?client_id=5650042&display=page&redirect_uri=https://oauth.vk.com/blank.html&scope=messages,wall&response_type=token&v=5.60">Button</a></div>--}}
<div class="row">
    <div class="col-md-12">
        <div class="col-md-2">
            <button class="btn btn-success" onclick="SendGet();">Запуск скрипта</button>
        </div>
        <div class="col-md-5"><h4>Status</h4><span class="label label-success">RUN</span></div>

    </div>
</div>
<div id="result">Тут будет ответ от сервера</div>

<div><label>id капчи</label><input type="text" size="20" id="idcpch" value=""></div>

<div><label>капча</label><img id="img" src="" alt=""></div>
<br>

{{--<div onclick="SendGet();">Запуск скрипта</div><br />--}}

<div><label>ввод капчи</label><input type="text" size="20" id="cpch"></div>

<div onclick="SendPost();">отправка капчи</div>
<br/>

</body>

<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function SendGet() {
        //отправляю GET запрос и получаю ответ
        console.log('gaas');
        $.ajax({
            type: "POST",
            url: '/script',
            dataType: 'json',
            data: {trues: true},
            success: function (data) {
//                alert(data);
                console.log(data);
                $("#img").attr("src", data.error.captcha_img);
                $("#idcpch").attr("value", data.error.captcha_sid);
            }
        });
    }

    function SendPost() {
        //отправляю POST запрос и получаю ответ
        $.ajax({
            type: "POST",
            url: '/script',
            data: {cpch: $("#cpch").val(), id: $("#idcpch").val(), trues: true},
            success: function (data) {
                console.log(data);
                $("#img").attr("src", data.error.captcha_img);
                $("#idcpch").attr("value", data.error.captcha_sid);
//                alert('капча отправлена');
            }
        });
    }

    function SendHead() {
        //отправляю HEAD запрос и получаю заголовок
        $.ajax({
            url: '/script',
            success: function (data) {
                alert(data);
            }
        });
    }
</script>
</html>
