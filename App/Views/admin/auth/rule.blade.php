@extends('layouts.admin')

@section('body')
<div class="white p20">
    <table class="layui-hide" id="test" lay-filter="test"></table>

    <!-- 表头 -->
    <script type="text/html" id="toolbarDemo">
        <div class="layui-btn-container">
            <button class="layui-btn layui-btn-normal layui-btn-sm" lay-event="add">添加权限</button>
        </div>
    </script>

    <!-- 菜单 -->
    <script type="text/html" id="switchMenu">
        <input type="checkbox" name="menu" value="@{{d.id}}" lay-skin="switch" lay-text="是|否" lay-filter="menu" @{{ d.menu == 1 ? 'checked' : '' }}>
    </script>

    <!-- 状态 -->
    <script type="text/html" id="switchStatus">
        <input type="checkbox" name="status" value="@{{d.id}}" lay-skin="switch" lay-text="启动|禁用" lay-filter="status" @{{ d.status == 1 ? 'checked' : '' }}>
    </script>


    <!-- 操作 -->
    <script type="text/html" id="barDemo">
      <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
      <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    </script>
</div>
@endsection


@section('javascriptFooter')
<script>
    layui.use('table', function(){
    var table = layui.table, form = layui.form;

    table.render({
        elem: '#test'
        ,'url':'/rule/get_all'
        ,method:'post'
        ,toolbar: '#toolbarDemo'
        ,title: '权限'
        ,cols: [[
        {field:'id', title:'ID', width:80, fixed: 'left'}
        ,{field:'name', title:'用户名', width:220, event:'edit_name'}
        ,{field:'node', title:'节点标记', width:220, event:'edit_node'}
        ,{field:'url', title:'路径', event:'edit_url'}
        ,{field:'menu', title:'是否菜单', templet: '#switchMenu', width:100}
        ,{field:'status', title:'是否启用', templet: '#switchStatus', width:100}
        ,{field:'created_at', title:'创建时间', width:220}
        ,{fixed: 'right', title:'操作', toolbar: '#barDemo', width: 180}
        ]]
        ,defaultToolbar:[]
        ,page: true
    });

    //头工具栏事件
    table.on('toolbar(test)', function(obj){
        var checkStatus = table.checkStatus(obj.config.id);
        switch(obj.event){
            case 'add':
                location.href = "/rule/add";
            break;
        };
    });

    form.on('switch(menu)', function(obj){
        let datajson = {key:'menu', value:obj.elem.checked ? '1':'0'};

        $.post('/rule/set/' + this.value ,datajson,function(data){
            if(data.code != 0) {
                layer.msg(data.msg);
                obj.elem.checked = !obj.elem.checked;
                form.render();
            }
        });
    });

    form.on('switch(status)', function(obj){
        let datajson = {key:'status', value:obj.elem.checked ? '1':'0'};

        $.post('/rule/set/' + this.value ,datajson,function(data){
            if(data.code != 0) {
                layer.msg(data.msg);
                obj.elem.checked = !obj.elem.checked;
                form.render();
            }
        });
    });



    //监听行工具事件
    table.on('tool(test)', function(obj){
        var data = obj.data;
        switch(obj.event){
            case 'del':
                layer.confirm('真的删除行么', function(index){
                    $.post('/rule/del/' + data.id ,'',function(data){
                        layer.close(index);
                        if(data.code != 0) {
                            layer.msg(data.msg);
                        } else {
                            obj.del();
                        }
                    });
                });
            break;
            case 'edit':
                location.href = '/rule/edit/' + data.id;
            break;
            case 'edit_name':
                layer.prompt({
                    formType: 2
                    ,value: data.name
                }, function(value, index){
                    layer.close(index);
                    let datajson = {key:'name', value:value};
                    $.post('/rule/set/' + data.id ,datajson,function(data){
                        if(data.code != 0) {
                            layer.msg(data.msg);
                        } else {
                            obj.update({
                              name: value
                            });
                        }
                    });
                });
            break;
            case 'edit_node':
                layer.prompt({
                    formType: 2
                    ,value: data.node
                }, function(value, index){
                    layer.close(index);
                    let datajson = {key:'node', value:value};
                    $.post('/rule/set/' + data.id ,datajson,function(data){
                        if(data.code != 0) {
                            layer.msg(data.msg);
                        } else {
                            obj.update({
                              node: value
                            });
                        }
                    });
                });
            break;
            case 'edit_url':
                layer.prompt({
                    formType: 2
                    ,value: data.url
                }, function(value, index){
                    layer.close(index);
                    let datajson = {key:'url', value:value};
                    $.post('/rule/set/' + data.id ,datajson,function(data){
                        if(data.code != 0) {
                            layer.msg(data.msg);
                        } else {
                            obj.update({
                              url: value
                            });
                        }
                    });
                });
            break;
        }
    });
});
</script>
@endsection
