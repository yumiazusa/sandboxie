<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="/public/css/layui.css">
        <title>财务共享沙盘模拟系统</title>

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
            th{
                text-align: center;
            }
        </style>
    </head>
    <body>
    @php
        $user = \Auth::guard('member')->user();
    @endphp
        <div class="flex-center position-ref full-height">

            <div class="content">
                <div class="title m-b-md">
                   财务共享服务中心<br>沙盘模拟系统
                </div>
                <!-- <div class="m-b-md">
                    @foreach($entities as $entity)
                        <a target="_blank" href="{{ route('web::entity.content.list', ['entityId' => $entity->id]) }}">{{ $entity->name }}</a><hr>
                    @endforeach
                </div> -->
                <div class="m-b-md">
                    @if($user)
                    <table class="layui-table">
                <thead>
                <tr>
                <th rowspan="2" style="text-align: center;">学号:{{ $user->studentid }}</th>
                <th style="text-align: center;"><a class="layui-btn layui-btn-fluid layui-btn-normal" style="text-decoration:none;" href='{{ url('strategy',) }}'>共享战略规划</a></th>
                <th style="text-align: center;"><a class="layui-btn layui-btn-fluid layui-btn-normal" style="text-decoration:none;" href='{{ url('plan',) }}'>共享组织规划</a></th>
                 <th style="text-align: center;"><a class="layui-btn layui-btn-fluid layui-btn-normal" style="text-decoration:none;" href='{{ url('feenav',) }}'>费用共享</a></th>
               <!--  <th style="text-align: center;"><a class="layui-btn layui-btn-fluid layui-btn-normal" style="text-decoration:none;" href='{{ url('salenav',) }}'>销售共享</a></th>
                <th style="text-align: center;"><a class="layui-btn layui-btn-fluid layui-btn-normal" style="text-decoration:none;" href='{{ url('purchasenav',) }}'>采购共享</a></th> -->
               <!--  <th style="text-align: center;"><a class="layui-btn layui-btn-fluid layui-btn-normal" style="text-decoration:none;" href='javascript:void(0);'>费用共享</a></th> -->
                <th style="text-align: center;"><a class="layui-btn layui-btn-fluid layui-btn-normal" style="text-decoration:none;" href='javascript:void(0);'>销售共享</a></th>
                <th style="text-align: center;"><a class="layui-btn layui-btn-fluid layui-btn-normal" style="text-decoration:none;" href='javascript:void(0);'>采购共享</a></th>
                 </tr>
                </thead>
                <tr><td  colspan="6"><a href="{{ route('member::logout') }}">退出登录</a></td></tr>
                </table>
                    @else
                        <a class="layui-btn layui-btn-fluid layui-btn-normal" style="text-decoration:none;" href="{{ route('member::login.show') }}">登录</a>
                        <!-- <a href="{{ route('admin::login.show') }}">后台登录</a> -->
                    @endif
                </div>
                <div class="m-b-md">
                   本沙盘功能仅供经管院财务共享综合实验教学使用，咨询Email：yumiazusa@hotmail.com
                </div>
            </div>
            <script type="text/javascript" charset="utf-8" async="" src="https://cdn.jsdelivr.net/npm/live2d-widget@3.1.4/lib/L2Dwidget.min.js"></script> 
    <script>
        L2Dwidget.init();
    </script>
<script type="text/javascript">
setTimeout(() => {
L2Dwidget.init({
"model": {
"scale": 0.93
},
"display": {
"position": "left",
"width": 200,
"height": 280,
"hOffset": 0,
"vOffset": 0
},
"mobile": {
"show": true,
"scale": 0.5
},
"react": {
"opacityDefault": 0.7,
"opacityOnHover": 0.2
}
});
}, 1000)
</script>

        </div>
    </body>
</html>
