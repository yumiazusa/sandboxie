@extends('admin.base')

@section('content')
    <div class="layui-card">

        @include('admin.breadcrumb')

        <div class="layui-card-body">
             <form class="form-horizontal 8 layui-form" role="form" action="{{route('admin::user.addex')}}" method="post" enctype="multipart/form-data">
                {{csrf_field()}}
            <!-- <div class="form-group">
                 
                <div class="col-md-8">
                    <input type="file" id="file" name="file"/>
                </div>

            </div>
            
            
            <div class="form-group" style="margin-top: 5px">
                <div class="col-md-offset-2 col-md-10">
                    <button type="submit" class="btn layui-btn-sm">提交</button>
                    <button type="reset" class="btn layui-btn-sm">取消</button>
                </div>
            </div> -->
            <div class="layui-upload">
  <button type="button" class="layui-btn layui-btn-normal" id="file" name='file'>选择文件</button><br>
  <button style="margin-top: 5px;" type="button" class="layui-btn" id="test9">开始上传</button><br>
  <button style="margin-top: 5px;" type="button" class="layui-btn layui-btn-primary layui-btn-sm" onclick="downloadExcel()">模板文件</button><br>
</div>
       </form>

            <!-- <form class="layui-form" action="@if(isset($id)){{ route('admin::user.update', ['id' => $id]) }}@else{{ route('admin::user.save') }}@endif" method="post">
                @if(isset($id)) {{ method_field('PUT') }} @endif
                <div class="layui-form-item">
                    <label class="layui-form-label">学号</label>
                    <div class="layui-input-block">
                        <input type="text" name="studentid" required  lay-verify="required" autocomplete="off" class="layui-input" value="{{ $model->studentid ?? ''  }}">
                    </div>
                </div>
                    @if(!isset($id))
                    <div class="layui-form-item">
                        <label class="layui-form-label">密码</label>
                        <div class="layui-input-inline">
                            <input type="password" name="password" required lay-verify="required" placeholder="请输入密码" autocomplete="off" class="layui-input">
                        </div>
                        <div class="layui-form-mid layui-word-aux">密码6到18位，不能为纯数字或纯字母</div>
                    </div>
                    @endif
                    <div class="layui-form-item">
                        <label class="layui-form-label">是否启用</label>
                        <div class="layui-input-block">
                            <input type="checkbox" name="status" lay-skin="switch" lay-text="启用|禁用" value="1" @if(isset($model) && $model->status == App\Model\Admin\User::STATUS_ENABLE) checked @endif>
                        </div>
                    </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit lay-filter="formAdminUser" id="submitBtn">提交</button>
                        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                    </div>
                </div>
            </form> -->
        </div>
    </div>
@endsection

@section('js')
    <script>
       // var form = layui.form;

        // form.on('submit(formAdminUser)', function(data){
        //     window.form_submit = $('#submitBtn');
        //     form_submit.prop('disabled', true);
        //     $.ajax({
        //         url: data.form.action,
        //         data: data.field,
        //         success: function (result) {
        //             if (result.code !== 0) {
        //                 form_submit.prop('disabled', false);
        //                 layer.msg(result.msg, {shift: 6});
        //                 return false;
        //             }
        //             layer.msg(result.msg, {icon: 1}, function () {
        //                 if (result.reload) {
        //                     location.reload();
        //                 }
        //                 if (result.redirect) {
        //                     location.href = '{!! url()->previous() !!}';
        //                 }
        //             });
        //         }
        //     });

        //     return false;
        // });


  layui.use('upload', function(){
  var $ = layui.jquery
  ,upload = layui.upload;

  //选完文件后不自动上传
  upload.render({
    elem: '#file'
    ,url: '{{route('admin::user.addex')}}' //改成您自己的上传接口
    ,auto: false
    ,multiple: true
    ,accept: 'file'
     ,bindAction: '#test9'
     ,done: function(res){
      layer.msg('上传成功');
      console.log(res)
    }
  });
});
   function downloadExcel() {
        //获取服务器地址
        var host = location.host;

        //把excel发送出来
        var url = "https://"+ host +"/admin/users/download";
        window.open(url);
    };
    </script>
@endsection
