@extends('admin/view')
@section('content')
    <h1>Links Create</h1>
    <p>
        <button id="logout" class="btn btn-default" style="float: right;">退出登录</button>
        <button id="back" class="btn btn-default" style="float: right; margin-right: 10px">返回</button>
    </p>
    <div style="text-align: center; width: 80%; margin-left: 10%; margin-top: 50px;">
        <form action="api/link/create" method="post" id="create">
            @csrf
            <div class="form-group">
                <label for="link_name">Link Name</label>
                <input type="text" class="form-control" autofocus id="link_name" placeholder="Link Name" name="link_name" required>
                <label id="errorName" for="link_name" style="visibility: hidden; color: red;">链接名不能为空</label>
            </div>
            <div class="form-group">
                <label for="link_url">Link URL</label>
                <input type="text" class="form-control" id="link_url" placeholder="Link URL" name="link_url" required>
                <label id="errorURL" for="link_url" style="visibility: hidden; color: red;">链接URL不能为空</label>
            </div>
        </form>
        <button class="btn btn-default" id="create-btn">Submit</button>
        <div>
            <label id="errorCreate" style="visibility: hidden; color: red;"></label>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#link_name").blur(function () {
                if ($('#link_name').val() === '') {
                    $('#errorName').css('visibility', '')
                } else {
                    $('#errorName').css('visibility', 'hidden')
                }
            })
            $("#link_name").focus(function () {
                $('#errorName').css('visibility', 'hidden')
                $('#errorCreate').css('visibility', 'hidden')
            })
            $("#link_url").blur(function () {
                if ($('#link_url').val() === '') {
                    $('#errorURL').css('visibility', '')
                } else {
                    $('#errorURL').css('visibility', 'hidden')
                }
            })
            $("#link_url").focus(function () {
                $('#errorURL').css('visibility', 'hidden')
                $('#errorCreate').css('visibility', 'hidden')
            })
            $("#create-btn").click(function () {
                if ($('#link_name').val() === '') {
                    $('#link_name').focus()
                    return
                }
                if ($('#link_url').val() === '') {
                    $('#link_url').focus()
                    return
                }
                $.ajax({
                    type: 'POST',
                    url: '/api/link/create',
                    data: {
                        link_name: $('#link_name').val(),
                        link_url: $('#link_url').val(),
                    },
                    dataType: 'json',
                    success: function (data) {
                        console.log(data.status)
                        if (data.status === 1) {
                            alert('Success Create')
                            $('#link_url').val('')
                            $('#link_name').val('')
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
                window.location.href = '/admin/link'
            })
        })
    </script>
@endsection