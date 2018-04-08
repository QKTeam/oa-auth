@extends('view')
@section('content')
    <div style="text-align: center; width: 80%; margin-left: 10%;">
        <h1>Welcome to QKteam's Portal</h1>
        <form action="api/login" method="post" id="login">
            @csrf
            <div class="form-group">
                <label for="usernameOrEmail">Username Or Email</label>
                <input type="text" class="form-control" autofocus id="usernameOrEmail" placeholder="Username/Email" name="usernameOrEmail" required>
                <label id="errorUsername" for="usernameOrEmail" style="visibility: hidden; color: red;">用户名不能为空</label>
            </div>
        </form>
        <button class="btn btn-default" id="reset-btn">Reset</button>
        <button id="back" class="btn btn-default">返回</button>
        <div>
            <label id="errorReset" style="visibility: hidden; color: red;"></label>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $("#usernameOrEmail").blur(function () {
                if ($('#usernameOrEmail').val() === '') {
                    $('#errorUsername').css('visibility', '')
                } else {
                    $('#errorUsername').css('visibility', 'hidden')
                }
            })
            $("#usernameOrEmail").focus(function () {
                $('#errorUsername').css('visibility', 'hidden')
                $('#errorLogin').css('visibility', 'hidden')
            })
            $("#reset-btn").click(function () {
                if ($('#usernameOrEmail').val() === '') {
                    $('#usernameOrEmail').focus()
                    return
                }
                $.ajax({
                    type: 'PUT',
                    url: '/api/reset',
                    data: {
                        username: $('#usernameOrEmail').val(),
                    },
                    dataType: 'json',
                    success: function (data) {
                        console.log(data)
                        if (data.status === 1) window.location.href = '/login'
                    },
                    error: function (e) {
                        switch (e.status) {
                            case 404:
                                $('#errorLogin').text('不存在的用户')
                                $('#errorLogin').css('visibility', '')
                                break
                            default:
                                $('#errorLogin').text(e.responseText)
                                $('#errorLogin').css('visibility', '')
                        }
                        console.error(e.responseText)
                    }
                })
            })
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
                        window.location.href = $('#from_url').val() + '?token=' + localStorage.getItem('token') + '&remember_token=' + localStorage.getItem('remember_token')
                    }
                },
                error: function (e) {
                    console.error(e.responseText)
                }
            })
            $('#back').click(function () {
                window.location.href = '/login'
            })
        })
    </script>
@endsection