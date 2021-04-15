<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="/layui/css/layui.css">
        <link rel="stylesheet" type="text/css" href="css/styleplan.css" />
        <script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
        <script type="text/javascript" src="js/drag.js"></script>
        <script type="text/javascript" src="js/jquery.flip.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui.min.js"></script>

        <title>组织规划沙盘</title>

        <!-- Styles -->
    </head>
    <body>
    @php
        $user = \Auth::guard('member')->user();
    @endphp
<div class='box box-4'>
  <button type="button" class="layui-btn layui-btn-danger layui-btn-sm" id="bu1" url="{{url('saveplan')}}"><i class="layui-icon">保存&nbsp;&#xe621;</i></button>
<button type="button" class="layui-btn layui-btn-sm  layui-btn-normal" id="bu5" title="重置" url="{{url('replan')}}"><i class="layui-icon">&#xe669;</i></button>
<button type="button" class="layui-btn layui-btn-sm  layui-btn-normal" id="bu6" title="返回首页"><i class="layui-icon">&#xe68e;</i></button>
@if ($user->studentid === "20201013")
     <button type="button" class="layui-btn layui-btn-sm  layui-btn-normal" id="bu7" title="刷新卡片" url="{{url('refee')}}"><i class="layui-icon">&#xe68e;</i></button>
     @endif
  <table class="layui-table table" >
  <tbody>
     <tr>
      <td id="tdjt">
        <table class="layui-table table1">
          <tbody>
          <tr>
            <td id="tdjt1">集团<br>
                          财务<br>
                          部门</td>
             <td>部门<br>卡片</td>
              <td></td>
          </tr>
           <tr>
            <td id="tdjt2">财务<br>
                          岗位</td>
             <td>岗位<br>卡片</td>
              <td></td>
          </tr>
          <tr>
            <td id="tdjt3">财务<br>
                          职责</td>
             <td>职责<br>卡片</td>
              <td></td>
          </tr>
        </tbody>
        </table>
      </td>
      <td id="tdgs">
        <table class="layui-table table2">
          <tbody>
          <tr>
            <td id="tdgs1">公司<br>
                          财务<br>
                          部门</td>
             <td>部门<br>卡片</td>
              <td></td>
          </tr>
           <tr>
            <td id="tdgs2">财务<br>
                          岗位</td>
             <td>岗位<br>卡片</td>
              <td></td>
          </tr>
          <tr>
            <td id="tdgs3">财务<br>
                          职责</td>
             <td>职责<br>卡片</td>
              <td></td>
          </tr>
        </tbody>
        </table>
      </td>
     </tr>
     <tr >
      <td id="tdjz">
        <table class="layui-table table4">
          <tbody>
          <tr>
            <td id="tdjz1">战略财务</td>
              <td id="tdjz3" rowspan="2">共享财务</td>
          </tr>
           <tr>
            <td id="tdjz2">业务财务</td>
          </tr>
        </tbody>
        </table>
      </td>
      <td id="tdgx">
        <table class="layui-table table3">
          <tbody>
          <tr>
            <td id="tdgx1">共享<br>
                          中心<br>部门</td>
              <td></td>
          </tr>
           <tr>
            <td id="tdgx2">财务<br>
                          岗位</td>
              <td></td>
          </tr>
          <tr>
            <td id="tdgx3">财务<br>
                          职责</td>
              <td></td>
          </tr>
        </tbody>
        </table>
      </td>
     </tr>
  </tbody>
</table>
 <dl><img src="img/05.jpg" width=90 height=10%></dl>
 <!-- @foreach($data as $k=>$v)
 <dl class="dl {{$data[$k]['kind']}}" id="dll" leftno="{{$data[$k]['left']}}" topno="{{$data[$k]['top']}}" cid="{{$data[$k]['cid']}}" kind="{{$data[$k]['kind']}}" name="{{$data[$k]['name']}}">
  <div class="card1">
  </div>
  <p>{{$data[$k]['name']}}</p>
</dl>
@endforeach -->
<!--  <dl class="dl" id="dll" leftno="13.5" topno="9">
  <div id='card2' class="card1">
  <p>财务经理</p>
 </div>
</dl> -->

    </div>
<script>

         $(function(){
            $('.box-4 dl').each(function(){
                var left = $(this).attr('leftno');
                var top = $(this).attr('topno');
                $(this).dragging({
                    move : 'both',
                    randomPosition :false,
                    left : left,
                    top: top
                });
            });
        });

        //离开保存
        $('#bu1').click(function(){
          var left =new Array;
          var top =new Array;
          var cid =new Array;
          var kind =new Array;
          var name =new Array;
          var saveurl = $(this).attr('url');
          var i = 0;
            $('.box-4 dl').each(function(){
            var offset = $(this).offset();
            left[i] = offset.left;
            top[i] = offset.top;
            cid[i] = $(this).attr('cid');
            kind[i] = $(this).attr('kind');
            name[i] = $(this).attr('name');
            i++;
        });
            $.ajax({
            type : "POST",
            dataType:"json",
            url : saveurl,
            data: {'cid':cid,'kind':kind,'name':name,'top':top,'left':left},
            beforeSend: function(){
                  layer.load();
                },
            //请求成功
            success : function(result) {
                layer.close();
                layer.msg(result.msg, {icon: result.code}, function () {
                        if (result.reload) {
                            location.reload();
                        }
                    });
            },
            //请求失败，包含具体的错误信息
            error : function(e){
                layer.msg(e.msg, {icon: e.code}, function () {
                        if (e.reload) {
                            location.reload();
                        }
                    });
            }
        });
        });
        //重置
        $('#bu5').click(function(){
            var reurl = $(this).attr('url');
            $.ajax({
            type : "POST",
            dataType:"json",
            url : reurl,
            data: "username=chen&nickname=alien",
            beforeSend: function(){
                  layer.load();
                },
            //请求成功
            success : function(result) {
                layer.close();
                layer.msg(result.msg, {icon: result.code}, function () {
                        if (result.reload) {
                            location.reload();
                        }
                    });
            },
            //请求失败，包含具体的错误信息
            error : function(e){
                layer.msg(e.msg, {icon: e.code}, function () {
                        if (e.reload) {
                            location.reload();
                        }
                    });
            }
        });
        });

        //返回首页
          $('#bu6').click(function(){
            window.location.replace('{{url('/')}}');
        });

         $('#bu7').click(function(){
        //     var reurl = $(this).attr('url');
        //     $.ajax({
        //     type : "POST",
        //     dataType:"json",
        //     url : reurl,
        //     data: "username=chen&nickname=alien",
        //     beforeSend: function(){
        //           layer.load();
        //         },
        //     //请求成功
        //     success : function(result) {
        //         layer.close();
        //         layer.msg(result.msg, {icon: result.code}, function () {
        //                 if (result.reload) {
        //                     location.reload();
        //                 }
        //             });
        //     },
        //     //请求失败，包含具体的错误信息
        //     error : function(e){
        //         layer.msg(e.msg, {icon: e.code}, function () {
        //                 if (e.reload) {
        //                     location.reload();
        //                 }
        //             });
        //     }
        // });

          var hightW = window.innerHeight;
          var widthW = window.innerWidth;
          var left =new Array;
          var top =new Array;
          var i = 0;
          $('.box-4 dl').each(function(){
            var offset = $(this).offset();
            left[i] = (parseFloat(offset.left / widthW)*100).toFixed(2);
            top[i] = (parseFloat(offset.top / hightW)*100).toFixed(2);
            alert(top);
            alert(left);
            i++;
          });
        });

    </script>
    <script src="/layui/layui.all.js"></script>
    </body>
</html>
