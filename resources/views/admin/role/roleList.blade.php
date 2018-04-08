@extends('admin/view')
<style>
    p.detail {
        color: #d62728;
        display: inline-block;
    }
</style>
@section('content')
<div>
    <h1>Roles List</h1>
    <p>
        <button id="logout" class="btn btn-default" style="float: right;">退出登录</button>
        <button id="back" class="btn btn-default" style="float: right; margin-right: 10px">返回</button>
    </p>
    <div>
        <h3><a href="https://fontawesome.com/icons?d=gallery">icon list</a></h3>
    </div>
    <div style="margin-top: 50px;">
        @foreach($roles as $role)
            <div style="border: 1px solid #8c8c8c; margin-bottom: 10px; font-size: 20px;">
                <div>Name: <p id="{{'role' . $role['id']}}" class="detail">{{$role['name']}}</p></div>
                <div>ShortName: <p class="detail">{{$role['short_name']}}</p></div>
                <div>Icon: <i class="fas fa-{{$role['icon']}}"></i><p class="detail">{{$role['icon']}}</p></div>
                <div>Color: <p class="detail" style="margin: 0; width: 20px; height: 20px; background-color: <?php echo $role['color'] ?>;"></p><p class="detail">{{$role['color']}}</p></div>
                <button class="btn btn-default" name="change" id="{{'change' . $role['id']}}" style="display: inline-block;">修改</button>
                <button class="btn btn-default" name="delete" id="{{'delete' . $role['id']}}" style="display: inline-block;">删除</button>
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
        var changeButton = $('button[name=change]')
        for (var i = 0; i < changeButton.length; i++) {
            $('#' + changeButton[i].id).click(function () {
                var id = this.id.match(pattern)[0]
                alert('为什么要修改呢，不如删除重新创建吧')
            })
        }
        var deleteButton = $('button[name=delete]')
        for (var i = 0; i < deleteButton.length; i++) {
            $('#' + deleteButton[i].id).click(function () {
                var id = this.id.match(pattern)[0]
                var flag = confirm('确认删除角色\n' + $('#role' + id).text())
                if (flag === false) return
                $.ajax({
                    type: 'DELETE',
                    url: '/api/role/delete',
                    data: {
                        role_id: id,
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