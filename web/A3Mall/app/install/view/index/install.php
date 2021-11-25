<section class="section">
    <div class="step">
        <ul>
            <li class="on"><em>1</em>检测环境</li>
            <li class="on"><em>2</em>创建数据</li>
            <li class="current"><em>3</em>完成安装</li>
        </ul>
    </div>

    <div class="install" id="log">
        <ul id="log-inner"></ul>
    </div>

    <div class="bottom tac">
        <a href="javascript:" class="btn_old">
            <img src="{__INSTALL_PATH__}/images/loading.gif" align="absmiddle"/>
            <span style="color: #fff">正在安装...</span>
        </a>
    </div>
</section>

<script type="text/javascript">
    $.ajaxSetup({cache: false});
    $(document).ready(function () {
        reloads();
    });

    function reloads(next, type) {
        $.ajax({
            type: "post",
            url: next || "{:str_replace('/install/','/',createUrl('index/install'))}",
            data: {type: type},
            dataType: "json",
            success: function (res) {
                try {
                    if (res.code && res.data) {
                        if (res.msg) {
                            insert_log(res.msg);
                        }

                        if (res.data.status) {
                            reloads(res.url, res.data.type);
                        } else {
                            setTimeout(function () {
                                self.location.href = res.url;
                            }, 2000);
                        }
                    } else {
                        insert_log(res.msg, true);
                    }
                } catch(e) {
                    insert_log(e.message, true);
                }
            },
            error: function (err) {
                insert_log('网络请求错误或内部服务器错误：', true);
                console.info('error：' + err.responseText);
            }
        });
    }

    function dateFormat(fmt) {
        const date = new Date();
        let format = fmt || "mm-dd HH:MM:SS";

        const opt = {
            "Y+": date.getFullYear().toString(),        // 年
            "m+": (date.getMonth() + 1).toString(),     // 月
            "d+": date.getDate().toString(),            // 日
            "H+": date.getHours().toString(),           // 时
            "M+": date.getMinutes().toString(),         // 分
            "S+": date.getSeconds().toString()          // 秒
        };

        if (/(y+)/.test(format)) {
            format.replace(RegExp.$1, (date.getFullYear() + "").substr(4 - RegExp.$1.length));
        }

        for (let k in opt) {
            if (new RegExp("(" + k + ")").test(format)) {
                format = format.replace(RegExp.$1, RegExp.$1.length === 1
                    ? opt[k]
                    : ("00" + opt[k]).substr(("" + opt[k]).length));
            }
        }

        return format;
    }

    function insert_log(msg, error) {
        let date = dateFormat();
        const icon = error ? "icon_error" : "icon_check";
        const message = '<li><i class="' + icon + '"/>' + msg + '<span class="fr">' + date + '</span></li>';

        $("#log-inner").append(message);
        $("#log").scrollTop(function () {
            return $(this)[0].scrollHeight
        });
    }
</script>
