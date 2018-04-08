@extends('admin/view')
@section('content')
    <h1>Members Create</h1>
    <p>
        <button id="logout" class="btn btn-default" style="float: right;">退出登录</button>
        <button id="back" class="btn btn-default" style="float: right; margin-right: 10px">返回</button>
    </p>
    <div style="text-align: center; width: 80%; margin-left: 10%; margin-top: 50px;">
        <form action="api/user/create" method="post" id="create">
            @csrf
            <div class="form-group">
                <label for="name">RealName</label>
                <input type="text" class="form-control" autofocus id="name" placeholder="RealName" name="name" required>
                <label id="errorName" for="name" style="visibility: hidden; color: red;">真实姓名不能为空</label>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" placeholder="Username" name="username" required>
                <label id="errorUsername" for="username" style="visibility: hidden; color: red;">用户名不能为空</label>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" placeholder="Email" name="email" required>
                <label id="errorEmail" for="email" style="visibility: hidden; color: red;">邮箱不能为空,且必须符合格式</label>
            </div>
            <div class="form-group">
                <label for="sex" style="display: block;">Sex</label>
                <label>
                    <input type="radio" name="sex" id="male" value="1" checked>
                    男
                </label>
                <label style="margin-left: 20px;">
                    <input type="radio" name="sex" id="female" value="0">
                    女
                </label>
            </div>
            <div class="form-group">
                <label for="role"></label>
                <select class="form-control" id="role">
                    @foreach($roles as $role)
                        <option id="{{'role' . $role['id']}}" value="{{$role['id']}}">{{$role['name']}}</option>
                    @endforeach
                </select>
            </div>
        </form>
        <button class="btn btn-default" id="create-btn">Submit</button>
        <div>
            <label id="errorCreate" style="visibility: hidden; color: red;"></label>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var pattern = /^(.*)?@(.*)?\.(.*)?$/
            $("#role3").attr('selected', "selected")
            $("#username").blur(function () {
                if ($('#username').val() === '') {
                    $('#errorUsername').css('visibility', '')
                } else {
                    $('#errorUsername').css('visibility', 'hidden')
                }
            })
            $("#username").focus(function () {
                $('#errorUsername').css('visibility', 'hidden')
                $('#errorCreate').css('visibility', 'hidden')
            })
            $("#name").blur(function () {
                if ($('#name').val() === '') {
                    $('#errorName').css('visibility', '')
                } else {
                    $('#errorName').css('visibility', 'hidden')
                }
            })
            $("#name").focus(function () {
                $('#errorName').css('visibility', 'hidden')
                $('#errorCreate').css('visibility', 'hidden')
            })
            $("#email").blur(function () {
                if ($('#email').val() === '') {
                    $('#errorEmail').css('visibility', '')
                } else if (!$('#email').val().match(pattern)) {
                    $('#errorEmail').css('visibility', '')
                } else {
                    $('#errorEmail').css('visibility', 'hidden')
                }
            })
            $("#email").focus(function () {
                $('#errorEmail').css('visibility', 'hidden')
                $('#errorCreate').css('visibility', 'hidden')
            })
            $("#create-btn").click(function () {
                console.log($('input[name=sex]:checked').val())
                console.log($('#role').val())
                if ($('#username').val() === '') {
                    $('#username').focus()
                    return
                }
                if ($('#name').val() === '') {
                    $('#name').focus()
                    return
                }
                if ($('#email').val() === '' || !$('#email').val().match(pattern)) {
                    $('#email').focus()
                    return
                }
                $.ajax({
                    type: 'POST',
                    url: '/api/user/create',
                    data: {
                        email: $('#email').val(),
                        username: $('#username').val(),
                        role_id: $('#role').val(),
                        name: $('#name').val(),
                        gender: $('input[name=sex]:checked').val(),
                    },
                    dataType: 'json',
                    success: function (data) {
                        console.log(data.status)
                        if (data.status === 1) {
                            alert('Success Create')
                            $('#email').val('')
                            $('#username').val('')
                            $('#name').val('')
                        }
                    },
                    error: function (e) {
                        var error_msg = 'Failed Create\n';
                        var errorArray = JSON.parse(e.responseText)
                        $.each(errorArray, function (index, value, array) {
                            error_msg += index + ': ' + value.toString() + '\n'
                        })
                        alert(error_msg)
                        console.error(e.responseText)
                    }
                })
            })
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
                window.location.href = '/admin/user'
            })
        })
    </script>
@endsection