{__NOLAYOUT__}
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{$data.title|raw|default="页面不存在！"}</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{__SYSTEM_PATH__}/js/bootstrap/css/bootstrap.min.css">
    <style type="text/css">
        ::-webkit-scrollbar{width:3px;height:3px}
        ::-webkit-scrollbar-track{background:#ccc!important}
        ::-webkit-scrollbar-thumb{background-color:#666!important}
        .container h2 {
            font-size: 16px;
            height: 35px;
            line-height: 35px;
            text-align: center;
        }
        .container h3 {
            color: #999;
            font-size: 14px;
            height: 25px;
            line-height: 25px;
        }
        .container h3 span {

        }
        .container .content-text {
            font-size: 14px;
        }
        .container .content-text p {
            height: 25px;
            line-height: 25px;
        }
        .container .content-read {
            color: #999;
            font-size: 0.6rem;
            text-align: right;
            margin-top: 0.5rem;
            line-height: 1rem;
        }
        .empty-box{
            margin-top: 50px;
            height: 35px;
            line-height: 35px;
            font-size: 18px;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="container">
        {if !empty($data)}
            <h2>{$data.title|default=""}</h2>
            <h3>
                <span style="color:#666">{$data.author|default=""}</span>
                <span style="margin-left:0.4rem">{$data.create_time|default=""}</span>
            </h3>
            {if $data.show_cover_pic}
            <div>
                <img src="{$data.local_url}" style="width:100%" alt="img">
            </div>
            {/if}
            <div class="content-text">
                {$data.content|raw|htmlspecialchars_decode|default=""}
            </div>
            <div class="content-read">阅读 {$data.visit|default="0"}</div>
        {else}
            <div class="empty-box">页面不存在！</div>
        {/if}
    </div>

</body>
</html>