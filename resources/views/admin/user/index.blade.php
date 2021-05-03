@extends('admin.base')

@section('content')
    @include('admin.breadcrumb')

    <div class="layui-card">
        <div class="layui-form layui-card-header light-search">
            <form>
                <input type="hidden" name="action" value="search">
            @include('admin.searchField', ['data' => App\Model\Admin\User::$searchField])
            <div class="layui-inline">
                <label class="layui-form-label">创建日期</label>
                <div class="layui-input-inline">
                    <input type="text" name="created_at" class="layui-input" id="created_at" value="{{ request()->get('created_at') }}">
                </div>
            </div>
            <div class="layui-inline">
                <button class="layui-btn layuiadmin-btn-list" lay-filter="form-search" id="submitBtn">
                    <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>
                </button>
            </div>
            </form>
        </div>
        <div class="layui-card-body">
            <table class="layui-table" lay-data="{url:'{{ route('admin::user.list') }}?{{ request()->getQueryString() }}', page:true, limit:50, id:'test', toolbar:'<div><a href=\'{{ route('admin::user.create') }}\'><i class=\'layui-icon layui-icon-add-1\'></i>新增会员</a>&nbsp;&nbsp;<a href=\'{{ route('admin::user.excel') }}\'><i class=\'layui-icon layui-icon-add-1\'></i>批量新增会员</a>&nbsp;&nbsp;<a class=\'renewcard\' href=\'javascript:void(0);\' url=\'{{url('refee')}}\'><i class=\'layui-icon layui-icon-add-1\'></i>重置卡片</a></div>'}" lay-filter="test">
                <thead>
                <tr>
                    <th lay-data="{field:'id', width:80, sort: true}">ID</th>
                    @include('admin.listHead', ['data' => App\Model\Admin\User::$listField])
                    <th lay-data="{field:'created_at'}">添加时间</th>
                    <th lay-data="{field:'updated_at'}">更新时间</th>
                    <th lay-data="{width:200, templet:'#action'}">操作</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
<script type="text/html" id="action">
    <a href="<% d.editUrl %>" class="layui-table-link" title="编辑"><i class="layui-icon layui-icon-edit"></i></a>
    <a href="javascript:;" class="layui-table-link" title="删除" style="margin-left: 10px" onclick="deleteMenu('<% d.deleteUrl %>')"><i class="layui-icon layui-icon-delete"></i></a>
     <a href="javascript:;" newurl="{{url('/admin/users/createNav')}}" class="layui-table-link" title="初始沙盘" style="margin-left: 10px" onclick="createnav('<% d.id %>' , '{{url('admin/users/createnav')}}')"><i class="layui-icon layui-icon-release"></i></a>
</script>

@section('js')
    <script>
        var laytpl = layui.laytpl;
        laytpl.config({
            open: '<%',
            close: '%>'
        });

        var laydate = layui.laydate;
        laydate.render({
            elem: '#created_at',
            range: '~'
        });

        function deleteMenu (url) {
            layer.confirm('确定删除？', function(index){
                $.ajax({
                    url: url,
                    data: {'_method': 'DELETE'},
                    success: function (result) {
                        if (result.code !== 0) {
                            layer.msg(result.msg, {shift: 6});
                            return false;
                        }
                        layer.msg(result.msg, {icon: 1}, function () {
                            if (result.reload) {
                                location.reload();
                            }
                            if (result.redirect) {
                                location.href = '{!! url()->previous() !!}';
                            }
                        });
                    }
                });

                layer.close(index);
            });
        }
        function createnav(id,url){
            $.ajax({
            type : "POST",
            dataType:"json",
            url : url,
            data: {'id':id},
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
        }
         $('.renewcard').click(function(){
            var url = $(this).attr('url');
             layer.confirm('选择要重置的卡片？', {
             btn: ['战略卡片','组织卡片','费用卡片','销售卡片','采购卡片','取消重置'] //按钮
            }, function(){
                layer.confirm('确定重置？', {
                btn: ['确定','取消'] },
                function(){
                  var kind = "strategy";
                  $.ajax({
                  type : "POST",
                  dataType:"json",
                  url : url,
                  data: {'kind':kind},
                  beforeSend: function(){
                  layer.msg('正在重置中');
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
                }
              );
            },function(){
                var kind = "plan";
                recard(kind);
            }

            );
         });

         function recard(kind){
            $.ajax({
                  type : "POST",
                  dataType:"json",
                  url : url,
                  data: {'kind':kind},
                  beforeSend: function(){
                  layer.msg('正在重置中');
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
            }
    </script>
@endsection
