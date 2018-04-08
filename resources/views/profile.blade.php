@extends('view')
<script type="text/javascript" src="../../js/sha1.js"></script>
@section('content')
    <div style="text-align: center; width: 80%; margin-left: 10%;">
        <p>
            <button id="logout" class="btn btn-default" style="float: right;">退出登录</button>
            <button id="back" class="btn btn-default" style="float: right; margin-right: 10px">返回</button>
        </p>
        <h1>Welcome to QKteam's Portal</h1>
        <form action="api/user/update" method="put" id="update">
            @csrf
            <div class="form-group">
                <label for="name">RealName</label>
                <input type="text" class="form-control" autofocus id="name" placeholder="RealName" disabled name="name" required value="{{$user['name']}}">
                <label id="errorName" for="name" style="visibility: hidden; color: red;">真实姓名不能为空</label>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" placeholder="Username" disabled name="username" required value="{{$user['username']}}">
                <label id="errorUsername" for="username" style="visibility: hidden; color: red;">用户名不能为空</label>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" placeholder="Email" name="email" disabled required value="{{$user['email']}}">
                <label id="errorEmail" for="email" style="visibility: hidden; color: red;">邮箱不能为空,且必须符合格式</label>
            </div>
            <div class="form-group">
                <label for="sex" style="display: block;">Sex</label>
                <label>
                    <input type="radio" name="sex" id="male" value="1" <?php if ($user['gender'] === 1) echo 'checked' ?> >
                    男
                </label>
                <label style="margin-left: 20px;">
                    <input type="radio" name="sex" id="female" value="0" <?php if ($user['gender'] === 0) echo 'checked' ?> >
                    女
                </label>
            </div>
            <div class="form-group">
                <label for="phone" style="display: block;">Phone</label>
                <input type="text" class="form-control" id="phone" placeholder="Phone" name="phone" value="{{$user['phone']}}">
                <label id="errorPhone" for="phone" style="visibility: hidden; color: red;">电话必须为11位数字</label>
            </div>
            <div class="form-group">
                <label for="changePassword" style="display: block;">New Password</label>
                <input type="password" class="form-control" id="change-password" placeholder="New Password" name="change-password">
            </div>
            <div class="form-group">
                <label for="confirmPassword" style="display: block;">Confirm New Password</label>
                <input type="password" class="form-control" id="confirm-password" placeholder="Confirm New Password" name="confirm-password">
                <label id="errorConfirm" for="confirmPassword" style="visibility: hidden; color: red;">必须和新密码相同</label>
            </div>
            <div class="form-group">
                <label for="password" style="display: block;">Old Password</label>
                <input type="password" class="form-control" id="password" placeholder="Enter Old Password" name="password">
                <label id="errorPassword" for="password" style="visibility: hidden; color: red;">请输入密码确认修改</label>
            </div>
        </form>
        <button class="btn btn-default" id="update-btn">Submit</button>
        <div>
            <label id="errorUpdate" style="visibility: hidden; color: red;"></label>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var pattern = /^\d{11}$/
            $("#name").blur(function () {
                if ($('#name').val() === '') {
                    $('#errorName').css('visibility', '')
                } else {
                    $('#errorName').css('visibility', 'hidden')
                }
            })
            $("#name").focus(function () {
                $('#errorName').css('visibility', 'hidden')
                $('#errorUpdate').css('visibility', 'hidden')
            })
            $("#phone").blur(function () {
                if ($('#phone').val() !== '' && !$('#phone').val().match(pattern)) {
                    $('#errorPhone').css('visibility', '')
                } else {
                    $('#errorPhone').css('visibility', 'hidden')
                }
            })
            $("#phone").focus(function () {
                $('#errorPhone').css('visibility', 'hidden')
                $('#errorUpdate').css('visibility', 'hidden')
            })
            $("#change-password").blur(function () {
                if (($('#confirm-password').val() !== '' && $('#change-password').val() !== '')
                    && $('#confirm-password').val() !== $('#change-password').val()) {
                    $('#errorConfirm').css('visibility', '')
                } else {
                    $('#errorConfirm').css('visibility', 'hidden')
                }
            })
            $("#confirm-password").blur(function () {
                if (($('#confirm-password').val() !== '' || $('#change-password').val() !== '')
                    && $('#confirm-password').val() !== $('#change-password').val()) {
                    $('#errorConfirm').css('visibility', '')
                } else {
                    $('#errorConfirm').css('visibility', 'hidden')
                }
            })
            $("#confirm-password").focus(function () {
                $('#errorConfirm').css('visibility', 'hidden')
                $('#errorUpdate').css('visibility', 'hidden')
            })
            $("#password").blur(function () {
                if ($('#password').val() === '') {
                    $('#errorPassword').css('visibility', '')
                } else {
                    $('#errorPassword').css('visibility', 'hidden')
                }
            })
            $("#password").focus(function () {
                $('#errorPassword').css('visibility', 'hidden')
                $('#errorUpdate').css('visibility', 'hidden')
            })
            $("#update-btn").click(function () {
                console.log($('input[name=sex]:checked').val())
                if ($('#phone').val() !== '' && !$('#phone').val().match(pattern)) {
                    $('#phone').focus()
                    return
                }
                if ($('#password').val() === '') {
                    $('#password').focus()
                    return
                }
                $.ajax({
                    type: 'PUT',
                    url: '/api/user/update/' + '<?php echo $user['id'] ?>',
                    data: {
                        token: localStorage.getItem('token'),
                        remember_token: localStorage.getItem('remember_token'),
                        gender: $('input[name=sex]:checked').val(),
                        phone: $('#phone').val(),
                        password: hex_sha1($('#password').val()),
                        new_password: $('#change-password').val() !== '' ? hex_sha1($('#change-password').val()) : null,
                    },
                    dataType: 'json',
                    success: function (data) {
                        console.log(data.status)
                        if (data.status === 1) {
                            alert('Success Update')
                            $('#password').val('')
                            $('#change-password').val('')
                            $('#confirm-password').val('')
                        }
                    },
                    error: function (e) {
                        var error_msg = 'Failed Create\n';
                        try {
                            var errorArray = JSON.parse(e.responseText)
                            $.each(errorArray, function (index, value) {
                                error_msg += index + ': ' + value.toString() + '\n'
                            })
                        }
                        catch (e) {}
                        alert(error_msg)
                        console.error(e.responseText)
                    }
                })
            })
            $('#logout').click(function () {
                localStorage.setItem('token', '')
                localStorage.setItem('remember_token', '')
                window.location.href = '/'
            })
            $('#back').click(function () {
                window.location.href = '/'
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
                    if (data.status !== 1) {
                        window.location.href = '/login'
                    }
                },
                error: function (e) {
                    console.error(e.responseText)
                    window.location.href = '/login'
                }
            })
        })
    </script>
@endsection