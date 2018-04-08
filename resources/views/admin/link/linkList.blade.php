@extends('admin/view')
<style>
    p.detail {
        color: #d62728;
        display: inline-block;
    }
</style>
@section('content')
<div>
    <h1>Links List</h1>
    <p>
        <button id="logout" class="btn btn-default" style="float: right;">退出登录</button>
        <button id="back" class="btn btn-default" style="float: right; margin-right: 10px">返回</button>
    </p>
    <div style="margin-top: 50px;">
        @foreach($links as $link)
            <div style="border: 1px solid #8c8c8c; margin-bottom: 10px; font-size: 20px;">
                <div>LinkName: <p id="{{'link' . $link['id']}}" class="detail">{{$link['link_name']}}</p></div>
                <div>LinkUrl: <p class="detail">{{$link['link_url']}}</p></div>
                <button class="btn btn-default" name="change" id="{{'change' . $link['id']}}" style="display: inline-block;">修改</button>
                <button class="btn btn-default" name="delete" id="{{'delete' . $link['id']}}" style="display: inline-block;">删除</button>
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
            window.location.href = '/admin/link'
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
                var flag = confirm('确认删除链接\n' + $('#link' + id).text())
                if (flag === false) return
                $.ajax({
                    type: 'DELETE',
                    url: '/api/link/delete',
                    data: {
                        link_id: id,
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