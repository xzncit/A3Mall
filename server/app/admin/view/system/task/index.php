<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;定时任务</a></li>
            <li><a href="javascript:;">任务列表</a></li>
        </ul>
    </div>
</div>

<section class="content clearfix">
    <div class="layui-list-box">
        <table class="layui-hide" id="list-box" lay-filter="list-box"></table>
    </div>
</section>

<script type="text/html" id="list-toolbar">
    <div class="layui-btn-container">
        <button lay-event="url" type="button" class="layui-btn layui-btn-sm layui-bg-light-blue"><i class="layui-icon">&#xe61f;</i> 添加</button>
        <button lay-event="refresh" type="button" class="layui-btn layui-btn-sm layui-bg-red"><i class="layui-icon">&#xe9aa;</i> 刷新</button>
    </div>
</script>

<script type="text/html" id="list-bar">
    <a class="layui-btn layui-btn-xs layui-btn-warm" lay-event="reset">重置</a>
    <a class="layui-btn layui-btn-xs layui-btn-normal" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>

<script type="text/html" id="checkboxTpl">
  <input type="checkbox" name="status" value="{{d.id}}" title="开启" lay-filter="status-filter" {{ d.status == 0 ? 'checked' : '' }}>
</script>

<script type="text/html" id="task-info">
   <p>任务名称：{{ d.title }}</p>
   <p>任务指令：{{ d.command }}</p>
</script>

<script type="text/html" id="task-status">
    <p>创建时间：{{ d.create_time }}（ 共执行 <b style="color: #FF5722;">{{ d.count }}</b> 次 ）</p>
    <p>计划时间：{{ d.exec_time }}</p>
    <p>
        执行时间：
        {{#  if(d.duration_time){ }}
            {{ d.start_time }} （ 耗时 <b style="color: #01AAED">{{ d.duration_time }}</b> 秒 ）
        {{#  } else { }}
            {{#  if(d.status == 2){ }}
                {{ d.start_time }}（ 任务执行中 ）
            {{#  } else { }}
                任务正在等待执行中...
            {{#  } }}
        {{#  } }}
    </p>
</script>

<script type="text/html" id="task-exec-status">
    <p>任务状态：
        {{#  if(d.status == 1){ }}
            {{#  if(d.end_time){ }}
                {{ d.end_time }}（ 上次执行时间 ）
            {{#  } else { }}
                任务未执行
            {{#  } }}
        {{#  } else if(d.status == 2) { }}
        任务正在执行中...
        {{#  } else if(d.status == 3) { }}
        执行成功
        {{#  } else if(d.status == 4) { }}
        执行失败{{# if(d.exec_desc) { }}（ d.exec_desc ）{{# } }}
        {{#  } }}
    </p>
    <p>任务类型：{{ d.exec_type == 1 ? "执行多次"  : "执行一次" }}</p>
    <p>执行时段：
        <b style="color: #1E9FFF">{{ d.value }}</b>

        {{#  if(d.type == 0){ }}
            天
        {{#  } else if(d.type == 1) { }}
            小时
        {{#  } else { }}
            分钟
        {{#  } }}
        执行一次
    </p>
</script>

<style type="text/css"> .layui-table-cell{ height:auto !important; } </style>

<script>
layui.use(['table','form'], function () {
    var table = layui.table;
    var form = layui.form;

    table.render({
        elem: '#list-box'
        , url: '{:createUrl("index")}'
        , toolbar: '#list-toolbar'
        , defaultToolbar: []
        , title: '数据表'
        , cols: [[
                  {type: 'checkbox'}
                , {field: 'title', title: '任务信息', templet: "#task-info", width: 350}
                , {field: 'title', title: '任务状态', templet: "#task-status"}
                , {field: 'title', title: '执行状态', templet: "#task-exec-status"}
                , {fixed: 'right', align: 'center', title: '操作', toolbar: '#list-bar', width: 190}
            ]]
        , text: {
            none: '<div><i class="layui-icon">&#xe702;</i>暂无相关数据</div>'
        }
        , page: true
        , height: 'full-200'
        // ,limit:30
    });

    //头工具栏事件
    table.on('toolbar(list-box)', function (obj) {
        switch (obj.event) {
            case 'url':
                window.location.href = "{:createUrl('editor')}";
                break;
            case 'refresh':
                window.location.reload();
                break;
        }
    });

    //监听行工具事件
    table.on('tool(list-box)', function (obj) {
        var data = obj.data;
        if (obj.event === 'del') {
            layer.confirm('你确定要删除吗？', function (index) {
                $.get('{:createUrl("delete")}',{
                    id : data.id
                },function(result){
                    layer.close(index);
                    if(result.code){
                        obj.del();
                    }else{
                        layer.msg(result.msg,{ icon : 2 });
                    }
                },"json");
            });
        } else if (obj.event === 'edit') {
            window.location.href = '{:createUrl("editor")}?id='+data.id;
        }else if(obj.event == "reset"){
            layer.confirm('需要重新运行任务吗？', function (index) {
                $.get('{:createUrl("reset")}',{
                    id : data.id
                },function(result){
                    layer.close(index);
                    if(result.code){
                        window.location.reload();
                    }else{
                        layer.msg(result.msg,{ icon : 2 });
                    }
                },"json");
            });
        }
    });

    //监听锁定操作
    form.on('checkbox(status-filter)', function(obj){
        var that = this;
        $.get('{:createUrl("field")}',{
            name : that.name,
            value : (obj.elem.checked ? 0 : 1),
            id : that.value
        },function (result){
            if(!result.code){
                layer.msg(result.msg,{ icon : 2 });
            }
        },"json");
    });
});
</script>





