<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="/layui/css/layui.css">
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
        <script type="text/javascript" src="js/drag1.js"></script>
        <script type="text/javascript" src="js/jquery.flip.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui.min.js"></script>

        <title>费用共享沙盘</title>

        <!-- Styles -->
    </head>
    <body>
    @php
        $user = \Auth::guard('member')->user();
    @endphp
<div class='box box-4'>
  <button type="button" class="layui-btn layui-btn-danger layui-btn-sm" id="bu1" url="{{url('savefee')}}"><i class="layui-icon">保存&nbsp;&#xe621;</i></button>
    <button type="button" class="layui-btn layui-btn-sm" id="bu2" url="{{url('flowsheet')}}"><i class="layui-icon">去画流程图&nbsp;&nbsp;&#xe62a;</i></button>
    @if ($sharetb['sharetb'] === 1)
    <button type="button" class="layui-btn layui-btn-sm" id="bu3"><i class="layui-icon">共享&nbsp;&#xe613;</i></button>
    <button type="button" class="layui-btn layui-btn-sm" id="bu4" style="display:none;"><i class="layui-icon">正常&nbsp;&#xe612;</i></button>
    @elseif ($sharetb['sharetb'] === 2)
    <button type="button" class="layui-btn layui-btn-sm" id="bu3" style="display:none;"><i class="layui-icon" >共享&nbsp;&#xe613;</i></button>
    <button type="button" class="layui-btn layui-btn-sm" id="bu4" ><i class="layui-icon">正常&nbsp;&#xe612;</i></button>
    @endif
     <button type="button" class="layui-btn layui-btn-sm  layui-btn-normal" id="bu5" title="重置" url="{{url('renav')}}"><i class="layui-icon">&#xe669;</i></button>
     <button type="button" class="layui-btn layui-btn-sm  layui-btn-normal" id="bu6" title="返回首页"><i class="layui-icon">&#xe68e;</i></button>
     <button type="button" class="layui-btn layui-btn-sm  layui-btn-normal" id="bu7" title="test"><i class="layui-icon">&#xe68e;</i></button>
<table class="layui-table table" id="sharetb" sharetb="{{$sharetb['sharetb']}}">
  <tbody>
    <tr class="tr">
      <td id="td1">角色</td>
      <td>角色<br>卡片</td>
      <td>正常流程参与角色</td>
      @if ($sharetb['sharetb'] === 2)
      <td>共享流程参与角色</td>
      @endif
    </tr>
    <tr class="tr">
      <td id="td2">动作</td>
      <td>动作<br>卡片</td>
      <td>正常业务动作</td>
       @if ($sharetb['sharetb'] === 2)
      <td>共享业务动作</td>
    @endif
    </tr>
    <tr class="tr">
      <td id="td3">单据</td>
      <td>单据<br>卡片</td>
      <td>正常业务单据</td>
       @if ($sharetb['sharetb'] === 2)
      <td>共享业务单据</td>
     @endif
    </tr>
    <tr class="tr">
      <td id="td4">技术</td>
      <td>技术<br>卡片</td>
      <td>正常业务支持技术</td>
       @if ($sharetb['sharetb'] === 2)
      <td>共享业务技术支持</td>
     @endif
    </tr>

  </tbody>
</table>

    <dl><img src="img/05.jpg" width=90 height=10%></dl>
   <!--  @foreach($data as $k=>$v)
    <dl class="dl" id="dll" leftno="{{$data[$k]['left']}}" topno="{{$data[$k]['top']}}" cid="{{$data[$k]['cid']}}" kind="{{$data[$k]['kind']}}" name="{{$data[$k]['name']}}" isshare="{{$data[$k]['isshare']}}" sharename="{{$data[$k]['sharename']}}">
      @if ($data[$k]['shared'] === 3)
      <div class="card2">
      @else
      @switch($data[$k]['shared'])
      @case(1)
       <div class="card1">
      @break
      @case(2)
       <div class="card2">
      @break
      @case(0)
       <div class="card1">
      @break
      @endswitch
      @endif
      <div class="{{$data[$k]['kind']}}">
        @switch($data[$k]['kind'])
        @case('people')
        <i class="layui-icon">&#xe66f;</i>
        @break
        @case('action')
        <i class="layui-icon">&#xe641;</i>
        @break
        @case('list')
        <i class="layui-icon">&#xe63c;</i>
        @break
        @case('tech')
        <i class="layui-icon">&#xe631;</i>
        @break
        @endswitch
      </div>
      @if ($data[$k]['shared'] === 3)
      <p id="{{$k}}p">{{$data[$k]['sharename']}}</p>
      @else
      @switch($data[$k]['shared'])
      @case(1)
      <p id="{{$k}}p">{{$data[$k]['name']}}</p>
      @break
      @case(2)
      <p id="{{$k}}p">{{$data[$k]['sharename']}}</p>
      @break
      @case(0)
      <p id="{{$k}}p">{{$data[$k]['name']}}</p>
      @break
      @endswitch
      @endif
      </div>
      
      @if ($data[$k]['isshare'] === 0)
       <i class="i" id="{{$k}}" shared="0" style="visibility:hidden"></i>
      @elseif ($data[$k]['isshare'] === 1)
      @switch($data[$k]['shared'])
      @case(3)
      <i class="i" id="{{$k}}" shared="3" style="visibility:hidden"></i>
      @break
      @case(1)
      <i class="layui-icon i" id="{{$k}}" shared="1" sharename="{{$data[$k]['name']}}" sharedname="{{$data[$k]['sharename']}}">&#xe65b;</i>
      @break
      @case(2)
       <i class="layui-icon i" id="{{$k}}" shared="2" sharename="{{$data[$k]['name']}}" sharedname="{{$data[$k]['sharename']}}">&#xe65a;</i>
      
      @break
      @endswitch
      @endif
       

       </dl>
      @endforeach -->
  

    
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
          var hightW = window.innerHeight;
          var widthW = window.innerWidth;
          var cid =new Array;
          var kind =new Array;
          var name =new Array;
          var isshare =new Array;
          var sharename =new Array;
          var shared =new Array;
          var saveurl = $(this).attr('url');
          var sharetb = $("#sharetb").attr('sharetb');
          var i = 0;
            $('.box-4 dl').each(function(){
            var offset = $(this).offset();
            left[i] = (offset.left / widthW).toFixed(3) *100;
            top[i] = (offset.top / hightW).toFixed(3) *100;
            cid[i] = $(this).attr('cid');
            kind[i] = $(this).attr('kind');
            name[i] = $(this).attr('name');
            isshare[i] = $(this).attr('isshare');
            sharename[i] = $(this).attr('sharename');
            shared[i] = $(this).children("i").attr('shared');
            i++;
        });
            $.ajax({
            type : "POST",
            dataType:"json",
            url : saveurl,
            data: {'cid':cid,'kind':kind,'name':name,'isshare':isshare,'sharename':sharename,'top':top,'left':left,'shared':shared,'sharetb':sharetb},
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
        //流程图
        $('#bu2').click(function(){
            layer.confirm('确定去画流程图？', {
             btn: ['确定','取消'] //按钮
            }, function(){
          var left =new Array;
          var top =new Array;
          var cid =new Array;
          var kind =new Array;
          var name =new Array;
          var isshare =new Array;
          var sharename =new Array;
          var shared =new Array;
          var saveurl = $('#bu1').attr('url');
          var url2 = $('#bu2').attr('url');
          var sharetb = $("#sharetb").attr('sharetb');
          var i = 0;
            $('.box-4 dl').each(function(){
            var offset = $(this).offset();
            left[i] = offset.left;
            top[i] = offset.top;
            cid[i] = $(this).attr('cid');
            kind[i] = $(this).attr('kind');
            name[i] = $(this).attr('name');
            isshare[i] = $(this).attr('isshare');
            sharename[i] = $(this).attr('sharename');
            shared[i] = $(this).children("i").attr('shared');
            i++;
        });
            $.ajax({
            type : "POST",
            dataType:"json",
            url : saveurl,
            data: {'cid':cid,'kind':kind,'name':name,'isshare':isshare,'sharename':sharename,'top':top,'left':left,'shared':shared,'sharetb':sharetb},
            beforeSend: function(){
                  layer.msg('正在保存中');
                },
            //请求成功
            success : function(result) {
                window.location.replace(url2);
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
        });
        //共享
        $('#bu3').click(function(){
            $("#bu4").css('display','block');
            $("#bu3").css('display','none');
            $("#sharetb").attr('sharetb',2);
            $('table tr').each(function() {
            var content = $(this).children().eq(2).html();
            content = content.slice(2);
   　    　   $(this).append("<td>共享"+content+"</td>");
            });
        });
        //返回正常
         $('#bu4').click(function(){
            $("#bu3").css('display','block');
            $("#bu4").css('display','none');
            $("#sharetb").attr('sharetb',1);
            $('table tr').each(function() {
            $(this).children().eq(3).remove();
            });
        });
        //重置
        $('#bu5').click(function(){
            var reurl = $(this).attr('url');
            var postdata = $(this).attr('title');
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

        //卡片翻转
        $(function(){
                $(".i").bind("click",function(){
                       var sharedno = $(this).attr('shared');
                       var sharedid = $(this).attr('id');
                       var sharename = $(this).attr('sharename');
                       var sharedname = $(this).attr('sharedname');
                       if(sharedno == 1){
                        $(this).parent().flip({
                        direction: 'lr',
                        speed: '0.5',
                        // onEnd: function(){$("#dll").css("background","");}
                        onEnd: function(){
                            $("#"+sharedid).attr('shared','2');
                            $("#"+sharedid).html('&#xe65a;');
                            $("#"+sharedid+"p").html(sharedname);
                            $("#"+sharedid+"p").parent().attr('class','card2');
                            }
                          });
                         }else if(sharedno == 2){
                            $(this).parent().flip({
                        direction: 'rl',
                        speed: '0.5',
                        // onEnd: function(){$("#dll").css("background","");}
                        onEnd: function(){
                             $("i#"+sharedid).attr('shared','1');
                             $("i#"+sharedid).html('&#xe65b;');
                             $("#"+sharedid+"p").html(sharename);
                             $("#"+sharedid+"p").parent().attr('class','card1');
                            }
                          });
                         }
                    return false;
                });

                // $(".revert").bind("click",function(){
                //     $("#flipbox").revertFlip();
                //     return false;
                // });

                // setInterval('alert("Hello");', 3000);

            });

         $('#bu7').click(function(){
          var hightW = window.innerHeight;
          var widthW = window.innerWidth;
          var left =new Array;
          var top =new Array;
          var i = 0;
          $('.box-4 dl').each(function(){
            var offset = $(this).offset();
            left[i] = (offset.left / widthW).toFixed(3) *100;
            top[i] = (offset.top / hightW).toFixed(3) *100;
            alert(top);
            alert(left);
            i++;
          });
        //   var left =new Array;
        //   var top =new Array;
        //   var cid =new Array;
        //   var kind =new Array;
        //   var name =new Array;
        //   var isshare =new Array;
        //   var sharename =new Array;
        //   var shared =new Array;
        //   var saveurl = $(this).attr('url');
        //   var sharetb = $("#sharetb").attr('sharetb');
        //   var i = 0;
        //     $('.box-4 dl').each(function(){
        //     var offset = $(this).offset();
        //     left[i] = offset.left;
        //     top[i] = offset.top;
        //     cid[i] = $(this).attr('cid');
        //     kind[i] = $(this).attr('kind');
        //     name[i] = $(this).attr('name');
        //     isshare[i] = $(this).attr('isshare');
        //     sharename[i] = $(this).attr('sharename');
        //     shared[i] = $(this).children("i").attr('shared');
        //     i++;
        // });
        //     $.ajax({
        //     type : "POST",
        //     dataType:"json",
        //     url : saveurl,
        //     data: {'cid':cid,'kind':kind,'name':name,'isshare':isshare,'sharename':sharename,'top':top,'left':left,'shared':shared,'sharetb':sharetb},
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
        });

    </script>
    <script src="/layui/layui.all.js"></script>
    </body>
</html>
