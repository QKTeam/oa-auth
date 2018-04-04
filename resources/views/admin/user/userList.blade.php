@extends('view')
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
    </p>
    <div style="margin-top: 50px;">
        @foreach($users as $user)
            <div style="border: 1px solid #8c8c8c; margin-bottom: 10px; font-size: 20px;">
                <div>UserName: <p class="detail"><?php echo $user['username'] ?></p></div>
                <div>Email: <p class="detail"><?php echo $user['email'] ?></p></div>
                <div>Role: <p class="detail"><?php echo $user['role_name'] ?></p></div>
                <div>Phone: <p class="detail"><?php echo $user['phone'] ?></p></div>
                <div>Name: <p class="detail"><?php echo $user['name'] ?></p></div>
                <div>Gender: <p class="detail"><?php echo $user['gender'] ?></p></div>
                <button class="btn btn-default" id="<?php echo $user['id'] ?>" style="margin-left: 50%">修改</button>
            </div>
        @endforeach
    </div>
</div>
<script>
    $(document).ready(function () {
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
                if (data.status === 0) {
                    window.location.href = '/login'
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
    })
</script>
@endsection