{__NOLAYOUT__}
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>跳转提示 | A3Mall</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{__SYSTEM_PATH__}/js/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="{__SYSTEM_PATH__}/font/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="{__SYSTEM_PATH__}/js/layui/css/layui.css">
    <link rel="stylesheet" href="{__SYSTEM_PATH__}/css/base.css">
    <style type="text/css">
        #jump-box { position: absolute; left: 50%; top: 50%; transform: translate(-50%,-50%); width: 370px; min-width: 370px; max-width: 450px; height: auto !important; height: 430px; min-height: 430px; }
        #jump-box .image {  }
        #jump-box .image img { display: block; margin: 0 auto; width: 277px; height: 198px; }
        #jump-box .title { padding-top: 25px; text-align: center; font-size: 28px; color: #333; }
        #jump-box .intro { padding-top: 25px; padding-bottom: 30px; border-bottom: 1px solid #cecece; text-align: center; font-size: 16px; color: #666; }
        #jump-box .btn { padding-top: 20px; text-align: center; width: 100%; }
        #jump-box .btn span:first-child { float: left; }
        #jump-box .btn span:last-child { float: right; }
        #jump-box .btn a { color: #fff; border-radius: 15px; display: inline-block; width: 155px; height: 40px; line-height: 40px; font-size: 16px; background-color: #3084b5; }
        #jump-box .btn a:hover { opacity: 0.8; }
    </style>
</head>
<body>

    <div id="jump-box">
        <div class="image"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAARUAAADGCAYAAAD8MxTXAAAACXBIWXMAAAsTAAALEwEAmpwYAAAF92lUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS42LWMxNDggNzkuMTY0MDM2LCAyMDE5LzA4LzEzLTAxOjA2OjU3ICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdEV2dD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlRXZlbnQjIiB4bWxuczpkYz0iaHR0cDovL3B1cmwub3JnL2RjL2VsZW1lbnRzLzEuMS8iIHhtbG5zOnBob3Rvc2hvcD0iaHR0cDovL25zLmFkb2JlLmNvbS9waG90b3Nob3AvMS4wLyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgMjEuMCAoTWFjaW50b3NoKSIgeG1wOkNyZWF0ZURhdGU9IjIwMjAtMTItMjFUMDI6MzE6NTErMDg6MDAiIHhtcDpNZXRhZGF0YURhdGU9IjIwMjAtMTItMjFUMDI6MzE6NTErMDg6MDAiIHhtcDpNb2RpZnlEYXRlPSIyMDIwLTEyLTIxVDAyOjMxOjUxKzA4OjAwIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjFmODEzMzg1LWM1NmQtNGY4Ny1hYmZmLWRhYWRmNGU4MzFmYyIgeG1wTU06RG9jdW1lbnRJRD0iYWRvYmU6ZG9jaWQ6cGhvdG9zaG9wOjUzZjVhOWFkLTQ1ODMtNGM0MC05NGRiLWIwNTdlMDNmMzc3MSIgeG1wTU06T3JpZ2luYWxEb2N1bWVudElEPSJ4bXAuZGlkOjEzNmY2Njg4LWNmMmItNDczYy04MGY2LWFmNzg5YTg3MzYxYyIgZGM6Zm9ybWF0PSJpbWFnZS9wbmciIHBob3Rvc2hvcDpDb2xvck1vZGU9IjMiIHBob3Rvc2hvcDpJQ0NQcm9maWxlPSJzUkdCIElFQzYxOTY2LTIuMSI+IDx4bXBNTTpIaXN0b3J5PiA8cmRmOlNlcT4gPHJkZjpsaSBzdEV2dDphY3Rpb249ImNyZWF0ZWQiIHN0RXZ0Omluc3RhbmNlSUQ9InhtcC5paWQ6MTM2ZjY2ODgtY2YyYi00NzNjLTgwZjYtYWY3ODlhODczNjFjIiBzdEV2dDp3aGVuPSIyMDIwLTEyLTIxVDAyOjMxOjUxKzA4OjAwIiBzdEV2dDpzb2Z0d2FyZUFnZW50PSJBZG9iZSBQaG90b3Nob3AgMjEuMCAoTWFjaW50b3NoKSIvPiA8cmRmOmxpIHN0RXZ0OmFjdGlvbj0ic2F2ZWQiIHN0RXZ0Omluc3RhbmNlSUQ9InhtcC5paWQ6MWY4MTMzODUtYzU2ZC00Zjg3LWFiZmYtZGFhZGY0ZTgzMWZjIiBzdEV2dDp3aGVuPSIyMDIwLTEyLTIxVDAyOjMxOjUxKzA4OjAwIiBzdEV2dDpzb2Z0d2FyZUFnZW50PSJBZG9iZSBQaG90b3Nob3AgMjEuMCAoTWFjaW50b3NoKSIgc3RFdnQ6Y2hhbmdlZD0iLyIvPiA8L3JkZjpTZXE+IDwveG1wTU06SGlzdG9yeT4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz5RMoBgAAAOvElEQVR42u2dX4xcZRmHz263u+3udmcasAmBmHBtSIwSCEmBInABYmKbYBO91QvBC1C4Unuh3gGBC8ULro0UEjARISaK8scQGhJj1FuNQEtSpZ3Zf+1uu1vf38weHOrOnO87c86Z7zvneZI3O4TZ2e3snOe83/t+f6bOnDmTlETL4jaLmy0+Z3GjxfUWbYvFBACqYNWiY6EL/R8Wf7d4z+Idi24ZP3CqYKncYHHc4pjFrRZ7+JsCBMmWxbsWL1m8aPF+aFK5w+Jxi/sQ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"></div>
        <div class="title"><?php echo(strip_tags($msg));?></div>
        <div class="intro">
            页面将在<b id="wait"> <?php echo($wait);?> </b>秒后自动跳转
        </div>
        <div class="btn">
            <span><a id="href" href="<?php echo($url);?>">返回上一页</a></span>
            <span><a href="{:createUrl('platform.index/index')}">跳转至首页</a></span>
        </div>
    </div>

    <script type="text/javascript">
        (function(){
            var wait = document.getElementById('wait'),
                href = document.getElementById('href').href;
            var interval = setInterval(function(){
                var time = --wait.innerHTML;
                if(time <= 0) {
                    location.href = href;
                    clearInterval(interval);
                };
            }, 1000);
        })();
    </script>
</body>
</html>
