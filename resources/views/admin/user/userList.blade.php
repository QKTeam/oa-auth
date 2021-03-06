@extends('admin/view')
<style>
    p.detail {
        color: #d62728;
        display: inline-block;
    }
</style>
@section('content')
<div>
    <h1>Members List</h1>
    <p>
        <button id="logout" class="btn btn-default" style="float: right;">退出登录</button>
        <button id="back" class="btn btn-default" style="float: right; margin-right: 10px">返回</button>
    </p>
    <div style="margin-top: 50px;">
        @foreach($users as $user)
            <div style="border: 1px solid #8c8c8c; margin-bottom: 10px; font-size: 20px;">
                <div>UserName: <p id="{{'user' . $user['id']}}" class="detail">{{$user['username']}}</p></div>
                <div>Email: <p class="detail">{{$user['email']}}</p></div>
                <div>Role: <p class="detail">{{$user['role_name']}}</p></div>
                <div>Phone: <p class="detail">{{$user['phone']}}</p></div>
                <div>Name: <p class="detail">{{$user['name']}}</p></div>
                <div>Gender: <p class="detail">{{$user['gender']}}</p></div>
                <button class="btn btn-default" name="change" id="{{'change' . $user['id']}}" style="display: inline-block;">修改</button>
                <button class="btn btn-default" name="delete" id="{{'delete' . $user['id']}}" style="display: inline-block;">删除</button>
            </div>
        @endforeach
    </div>
</div>
<script>
    $(document).ready(function () {
        var pattern = /\d+/
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
        var changeButton = $('button[name=change]')
        for (var i = 0; i < changeButton.length; i++) {
            $('#' + changeButton[i].id).click(function () {
                var id = this.id.match(pattern)[0]
                window.location.href = '/admin/user/update/' + id
            })
        }
        var deleteButton = $('button[name=delete]')
        for (var i = 0; i < deleteButton.length; i++) {
            $('#' + deleteButton[i].id).click(function () {
                var id = this.id.match(pattern)[0]
                var flag = confirm('确认删除用户\n' + $('#user' + id).text())
                if (flag === false) return
                $.ajax({
                    type: 'DELETE',
                    url: '/api/user/delete',
                    data: {
                        user_id: id,
                    },
                    dataType: 'json',
                    success: function (data) {
                        console.log(data.status)
                        if (data.status === 1) {
                            alert('Success Delete')
                            window.location.reload()
                        }
                    },
                    error: function (e) {
                        console.error(e)
                    }
                })
            })
        }
    })
</script>
@endsection