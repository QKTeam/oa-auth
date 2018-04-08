@extends('view')
<style>
    .link {
        font-size: 24px;
    }
</style>
@section('content')
<div>
    <h1>Welcome to QKteam's Portal</h1>
    <p>
        <button id="logout" class="btn btn-default" style="float: right; display: none;">退出登录</button>
        <button id="login" class="btn btn-default" style="float: right;">登录</button>
        <button id="update" class="btn btn-default" style="float: right; margin-right: 20px;">修改个人信息</button>
    </p>
    <div>跳转链接</div>
    @foreach($links as $link)
        <?php echo "<div><a class=\"link\" id=\"" . $link["id"] . "\" href=\"" . $link["link_url"] . "\">" . $link["link_name"] . "</a></div>" ?>
    @endforeach
</div>
<script>
    $(document).ready(function () {
        var links = $(".link")
        for (var i = 0; i < links.length; i += 1) {
            $("#" + links[i].id).attr("href", $("#" + links[i].id).attr("href") + "/?token=" + localStorage.getItem('token') + "&remember_token=" + localStorage.getItem('remember_token'))
        }
        $.ajax({
            type: 'GET',
            url: '/api/logStatus',
            data: {
                token: localStorage.getItem('token'),
                remember_token: localStorage.getItem('remember_token')
            },
            dataType: 'json',
            success: function (data) {
                console.log(data.status)
                if (data.status === 1) {
                    $('#logout').css('display', 'inline-block')
                    $('#login').css('display', 'none')
                } else {
                    $('#login').css('display', 'inline-block')
                    $('#logout').css('display', 'none')
                }
            },
            error: function (e) {
                console.error(e)
            }
        })
        $('#login').click(function () {
            window.location.href = '/login'
        })
        $('#logout').click(function () {
            localStorage.setItem('token', '')
            localStorage.setItem('remember_token', '')
            $('#login').css('display', 'inline-block')
            $('#logout').css('display', 'none')
        })
        $('#update').click(function () {
            window.location.href = '/update?token=' + localStorage.getItem('token') + '&remember_token=' + localStorage.getItem('remember_token')
        })
    })
</script>
@endsection