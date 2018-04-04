@extends('view')
<script type="text/javascript" src="../../js/sha1.js"></script>
@section('content')
    @if($errors->any())
        <ul>
            @foreach($errors->all() as $key => $value)
                <li>{{ $key . "=>" . $value }}</li>
            @endforeach
        </ul>
    @endif
    <div style="text-align: center; width: 80%; margin-left: 10%;">
        <h1>Welcome to QKteam's Portal</h1>
        <form action="api/login" method="post" id="login">
            @csrf
            <div class="form-group">
                <label for="usernameOrEmail">Username Or Email</label>
                <input type="text" class="form-control" autofocus id="usernameOrEmail" placeholder="Username/Email" name="usernameOrEmail" required>
                <label id="errorUsername" for="usernameOrEmail" style="visibility: hidden; color: red;">用户名不能为空</label>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" placeholder="Password" name="password" required>
                <label id="errorPassword" for="password" style="visibility: hidden; color: red;">密码不能为空</label>
            </div>
            <div class="form-group">
                <input type="checkbox" id="remember" name="remember" value="remember">
                <label>记住我</label>
            </div>
            <input type="hidden" id="from" name="from" required value="{{$value['from']}}">
            <input type="hidden" id="from_url" name="from_url" required value="{{$value['from_url']}}">
        </form>
        <button class="btn btn-default" id="login-btn">Submit</button>
        <div>
            <label id="errorLogin" for="password" style="visibility: hidden; color: red;"></label>
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
            $("#password").blur(function () {
                if ($('#password').val() === '') {
                    $('#errorPassword').css('visibility', '')
                } else {
                    $('#errorPassword').css('visibility', 'hidden')
                }
            })
            $("#usernameOrEmail").focus(function () {
                $('#errorUsername').css('visibility', 'hidden')
                $('#errorLogin').css('visibility', 'hidden')
            })
            $("#login-btn").click(function () {
                if ($('#usernameOrEmail').val() === '') {
                    $('#usernameOrEmail').focus()
                    return
                }
                if ($('#password').val() === '') {
                    $('#password').focus()
                    return
                }
                $.ajax({
                    type: 'POST',
                    url: '/api/login',
                    data: {
                        username: $('#usernameOrEmail').val(),
                        password: hex_sha1($('#password').val()),
                        from: $('#from').val(),
                        from_url: $('#from_url').val(),
                        remember: $('#remember:checked').length === 1 ? true : false,
                    },
                    dataType: 'json',
                    success: function (data) {
                        localStorage.setItem('token', data.token)
                        localStorage.setItem('remember_token', data.remember_token)
                        window.location.href = $('#from_url').val() + '?token=' + data.token + ';remember_token=' + data.remember_token
                        console.log(data)
                    },
                    error: function (e) {
                        switch (e.status) {
                            case 404:
                                $('#errorLogin').text('不存在的用户')
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
                        window.location.href = $('#from_url').val() + '?token=' + localStorage.getItem('token') + ';remember_token=' + localStorage.getItem('remember_token')
                    }
                },
                error: function (e) {
                    console.error(e.responseText)
                }
            })
        })
    </script>
@endsection