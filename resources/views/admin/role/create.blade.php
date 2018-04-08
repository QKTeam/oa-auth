@extends('admin/view')
@section('content')
    <h1>Roles Create</h1>
    <p>
        <button id="logout" class="btn btn-default" style="float: right;">退出登录</button>
        <button id="back" class="btn btn-default" style="float: right; margin-right: 10px">返回</button>
    </p>
    <div style="text-align: center; width: 80%; margin-left: 10%; margin-top: 50px;">
        <form action="api/role/create" method="post" id="create">
            @csrf
            <div class="form-group">
                <label for="name">Role Name</label>
                <input type="text" class="form-control" autofocus id="name" placeholder="Role Name" name="name" required>
                <label id="errorName" for="name" style="visibility: hidden; color: red;">角色名不能为空</label>
            </div>
            <div class="form-group">
                <label for="short_name">Short Name</label>
                <input type="text" class="form-control" id="short_name" placeholder="Please use English Abbreviation" name="short_name" required>
                <label id="errorShortName" for="short_name" style="visibility: hidden; color: red;">角色缩写不能为空</label>
            </div>
            <div class="form-group">
                <label for="icon">Icon</label>
                <p>Please find icon in <a href="https://fontawesome.com/icons?d=gallery" target="_blank" style="color: red">this web!</a></p>
                <div>Preview: <i id="preview-icon" class="fas fa-"></i></div>
                <input type="text" class="form-control" id="icon" placeholder="Please use the Solid Icon" name="icon" required>
                <label id="errorIcon" for="icon" style="visibility: hidden; color: red;">角色图标不能为空</label>
            </div>
            <div class="form-group">
                <label for="color">Color</label>
                <div>Preview: <p id="preview-color" style="display: inline-block; margin: 0; width: 20px; height: 20px;"></p></div>
                <input type="text" class="form-control" id="color" placeholder="Please use Hex Color, begin with #" name="color" required>
                <label id="errorColor" for="color" style="visibility: hidden; color: red;">角色颜色不能为空, 且必须为#开头的6位16进制数</label>
            </div>
        </form>
        <button class="btn btn-default" id="create-btn">Submit</button>
        <div>
            <label id="errorCreate" style="visibility: hidden; color: red;"></label>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var pattern = /^#[a-fA-F0-9]{6}$/
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
            $("#short_name").blur(function () {
                if ($('#short_name').val() === '') {
                    $('#errorShortName').css('visibility', '')
                } else {
                    $('#errorShortName').css('visibility', 'hidden')
                }
            })
            $("#short_name").focus(function () {
                $('#errorShortName').css('visibility', 'hidden')
                $('#errorCreate').css('visibility', 'hidden')
            })
            $("#icon").blur(function () {
                if ($('#icon').val() === '') {
                    $('#errorIcon').css('visibility', '')
                    $('#preview-icon').removeClass()
                } else {
                    $('#errorIcon').css('visibility', 'hidden')
                    $('#preview-icon').removeClass()
                    $('#preview-icon').addClass('fas fa-' + $('#icon').val())
                }
            })
            $("#icon").focus(function () {
                $('#errorIcon').css('visibility', 'hidden')
                $('#errorCreate').css('visibility', 'hidden')
            })
            $("#color").blur(function () {
                if ($('#color').val() === '') {
                    $('#errorColor').css('visibility', '')
                    $('#preview-color').css('background-color', '')
                } else if (!$('#color').val().match(pattern)) {
                    $('#errorColor').css('visibility', '')
                    $('#preview-color').css('background-color', '')
                }
                else {
                    $('#errorColor').css('visibility', 'hidden')
                    $('#preview-color').css('background-color', $('#color').val())
                }
            })
            $("#color").focus(function () {
                $('#errorColor').css('visibility', 'hidden')
                $('#errorCreate').css('visibility', 'hidden')
            })
            $("#create-btn").click(function () {
                if ($('#name').val() === '') {
                    $('#name').focus()
                    return
                }
                if ($('#short_name').val() === '') {
                    $('#short_name').focus()
                    return
                }
                if ($('#icon').val() === '') {
                    $('#icon').focus()
                    return
                }
                if ($('#color').val() === '' || !$('#color').val().match(pattern)) {
                    $('#color').focus()
                    return
                }
                $.ajax({
                    type: 'POST',
                    url: '/api/role/create',
                    data: {
                        name: $('#name').val(),
                        short_name: $('#short_name').val(),
                        icon: $('#icon').val(),
                        color: $('#color').val(),
                    },
                    dataType: 'json',
                    success: function (data) {
                        console.log(data.status)
                        if (data.status === 1) {
                            alert('Success Create')
                            $('#name').val('')
                            $('#short_name').val('')
                            $('#icon').val('')
                            $('#color').val('')
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
                window.location.href = '/admin/role'
            })
        })
    </script>
@endsection