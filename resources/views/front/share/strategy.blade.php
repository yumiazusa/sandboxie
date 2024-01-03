<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <link rel="stylesheet" href="/layui/css/layui.css">
        <link rel="stylesheet" type="text/css" href="css/stylestrategy.css" />
        <script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
        <script type="text/javascript" src="js/drag1.js"></script>
        <script type="text/javascript" src="js/jquery.flip.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui.min.js"></script>

        <title>战略规划沙盘</title>

        <!-- Styles -->
    </head>
    <body>
    @php
        $user = \Auth::guard('member')->user();
    @endphp
<div class='box box-4'>
  <button type="button" class="layui-btn layui-btn-danger layui-btn-sm" id="bu1" url="{{url('savestrategy')}}"><i class="layui-icon">保存&nbsp;&#xe621;</i></button>
<button type="button" class="layui-btn layui-btn-sm  layui-btn-normal" id="bu5" title="重置" url="{{url('restrategy')}}"><i class="layui-icon">&#xe669;</i></button>
<button type="button" class="layui-btn layui-btn-sm  layui-btn-normal" id="bu6" title="返回首页"><i class="layui-icon">&#xe68e;</i></button>
<!--  @if ($user->studentid === "20201013")
     <button type="button" class="layui-btn layui-btn-sm  layui-btn-normal" id="bu7" title="刷新卡片" url="{{url('refee')}}"><i class="layui-icon">&#xe68e;</i></button>
     @endif -->
  <table class="layui-table table" >
  <tbody>
     <tr>
      <td id="tdms">
        <table class="layui-table table1">
          <tbody>
          <tr>
            <td id="tdms1">FSSC名称</td>
             <td>
      <input type="text" id="title" name="title" placeholder="输入共享服务中心名称" autocomplete="off" class="layui-input" value="{{$content['name']}}">
             </td>
              <td rowspan="3"><div id="location2">服务中心<br>建设模式</div>
              <table class="layui-table table5"><tr><td id="td1">短期试点</td></tr><tr><td id="td2">长期规划</td></tr></table>
              </td>
          </tr>
           <tr>
            <td id="tdms2">服务对象</td>
             <!-- <td>共享服务中心服务对象</td> -->
             <td><table class="layui-table table2"><tr><td>初阶服务对象</td><td>长阶服务对象</td></tr></table></td>
          </tr>
          <tr>
            <td id="tdms3">服务内容</td>
            <!--  <td>共享服务中心服务内容</td> -->
            <td><table class="layui-table table2"><tr><td>首期服务内容</td><td>远期服务内容</td></tr></table></td>
          </tr>
        </tbody>
        </table>
      </td>
      <td id="tdgs" rowspan="2">
        <div class="m_map">
        </div>
        <div id="reason"><textarea name="reason" maxlength="60" placeholder="选址依据(60字以内)" class="layui-textarea">{{$content['reason']}}</textarea></div>
      </td>
     </tr>
     <tr >
      <td id="tdlw">
        <div id='location'>战略定位</div>
        <table class="layui-table table4">
          <tbody>
          <tr>
            <td>短期战略</td>
            <td>中期战略</td>
          </tr>
           <tr>
            <td>长期战略</td>
            <td>发展目标</td>
          </tr>
        </tbody>
        </table>
      </td>
     </tr>
  </tbody>
</table>
<!--  <dl><img src="img/05.jpg" width=90 height=10%></dl> -->
 @foreach($data as $k=>$v)
 <dl class="dl {{$data[$k]['kind']}}" id="dll" leftno="{{$data[$k]['left']}}" topno="{{$data[$k]['top']}}" cid="{{$data[$k]['cid']}}" kind="{{$data[$k]['kind']}}" name="{{$data[$k]['name']}}">
  <div class="card1">
  </div>
  <p>{{$data[$k]['name']}}</p>
</dl>
@endforeach

    </div>
    <script src="http://fast.cgshiyan.com/js/iframe.js" id="fastgpt-iframe" data-src="https://fast.cgshiyan.com/chat/share?shareId=tpaqvptxakgvy28g9saj4oic" data-color="#4e83fd"></script>
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
          var hightW = window.innerHeight;
          var widthW = window.innerWidth;
          var cid =new Array;
          var kind =new Array;
          var name =new Array;
          var title = $('#title').val();
          var reason = $('#reason').children('textarea').val();
          var saveurl = $(this).attr('url');
          var i = 0;
            $('.box-4 dl').each(function(){
            var offset = $(this).offset();
            left[i] = (parseFloat(offset.left / widthW)*100).toFixed(2);
            top[i] = (parseFloat(offset.top / hightW)*100).toFixed(2);
            cid[i] = $(this).attr('cid');
            kind[i] = $(this).attr('kind');
            name[i] = $(this).attr('name');
            i++;
        });
            $.ajax({
            type : "POST",
            dataType:"json",
            url : saveurl,
            data: {'cid':cid,'kind':kind,'name':name,'top':top,'left':left,'title':title,'reason':reason},
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

          // var hightW = window.innerHeight;
          // var widthW = window.innerWidth;
          // var left =new Array;
          // var top =new Array;
          // var i = 0;
          // $('.box-4 dl').each(function(){
          //   var offset = $(this).offset();
          //   left[i] = (parseFloat(offset.left / widthW)*100).toFixed(2);
          //   top[i] = (parseFloat(offset.top / hightW)*100).toFixed(2);
          //   alert(top);
          //   alert(left);
          //   i++;
          // });
        });

    </script>
    <script src="/layui/layui.all.js"></script>
    </body>
</html>
