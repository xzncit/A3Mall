<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;订单管理</a></li>
            <li><a href="javascript:;">收款详情</a></li>
        </ul>
    </div>
</div>

<section class="content clearfix">
    <div class="layui-editor-box">
        <div class="layui-tab layui-tab-brief layui-tab-bg layui-tab-content-box">
            <ul class="layui-tab-title">
                <li class="layui-this">基本信息</li>
            </ul>
            
            <form action="" class="layui-form layui-form-pane">
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">


                            <div class="layui-form-item">
                                <table class="layui-table">
                                    <colgroup>
                                        <col width="10%">
                                        <col width="25%">
                                        <col width="10%">
                                        <col width="25%">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th colspan="4">操作信息：</th>
                                        </tr> 
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th style="text-align:right;">订单号：</th>
                                            <td>{$data.order_no}</td>
                                            <th style="text-align:right;">订单时间：</th>
                                            <td>{$data.create_time}</td>
                                        </tr>
                                        
                                        <tr>
                                            <th style="text-align:right;">支付方式：</th>
                                            <td>{$data.pname}</td>
                                            <th style="text-align:right;">收款方式：</th>
                                            <td>{if $data.type==1}线上付款{elseif $data.type==2}线下方式{/if}</td>
                                        </tr>
                                        
                                        <tr>
                                            <th style="text-align:right;">付款人：</th>
                                            <td>{$data.username}</td>
                                            <th style="text-align:right;">付款时间：</th>
                                            <td>{$data.pay_time}</td>
                                        </tr>
                                        
                                        <tr>
                                            <th style="text-align:right;">金额：</th>
                                            <td colspan="3">{$data.amount}</td>
                                        </tr>
                                        
                                        <tr>
                                            <th style="text-align:right;">操作员：</th>
                                            <td colspan="3">{$data.admin_name}</td>
                                        </tr>
                                        
                                        <tr>
                                            <th style="text-align:right;">备注：</th>
                                            <td colspan="3">{$data.note}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>


                    </div>

                </div>
                
            </form>
            
        </div>
    </div>
</section>

<script type="text/javascript">
    $(function () {
        layui.use(["form", "element",'layer'], function () {
            var form = layui.form;
            
            form.on('submit(layui-submit-filter)', function (data) {
                
                return false;
            });
        });
    });
</script>