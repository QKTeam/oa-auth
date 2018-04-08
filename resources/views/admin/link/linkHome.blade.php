@extends('admin/view')
<style>
    .link {
        font-size: 24px;
    }
</style>
@section('content')
<div>
    <h1>Links Controller</h1>
    <p>
        <button id="logout" class="btn btn-default" style="float: right;">退出登录</button>
        <button id="back" class="btn btn-default" style="float: right; margin-right: 10px">返回</button>
    </p>
    <div>跳转链接</div>
    <div>
        @foreach($links as $link)
            <?php echo "<div><a class=\"link\" href=\"" . $link["link_url"] . "\">" . $link["link_name"] . "</a></div>" ?>
        @endforeach
    </div>
</div>
<script>
    $(document).ready(function () {
        $.ajax({
            type: 'GET',
            url: '/api/isAdmin',
            data: {
                token: localStorage.getItem('token'),
                remember_token: localStorage.getItem('remember_token')
            },
            dataType: 'json',
            success: function (data) {
                console.log(data.status)
                if (data.status === 0) {
                    window.location.href = '/'
                }
            },
            error: function (e) {
                console.error(e)
            }
        })
        $('#logout').click(function () {
            localStorage.setItem('token', '')
            localStorage.setItem('remember_token', '')
            window.location.href = '/'
        })
        $('#back').click(function () {
            window.location.href = '/admin'
        })
    })
</script>
@endsection