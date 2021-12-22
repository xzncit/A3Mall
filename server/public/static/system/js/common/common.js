
function setUEUpload(id,url){
    UE.registerUI('上传图片', function(editor, uiName) {
        editor.registerCommand(uiName, {
            execCommand: function() {
                layer.open({
                    type: 2,
                    title: '图库列表',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['1100px', '600px'],
                    content: url
                });
            }
        });

        var btn = new UE.ui.Button({
            name: uiName,
            title: uiName,
            cssRules: 'background-position: -380px 0px;',
            onclick: function() {
                editor.execCommand(uiName);
            }
        });

        editor.addListener('selectionchange', function() {
            var state = editor.queryCommandState(uiName);
            if (state == -1) {
                btn.setDisabled(true);
                btn.setChecked(false);
            } else {
                btn.setDisabled(false);
                btn.setChecked(state);
            }
        });

        return btn;
    });

    // return UE.getEditor('container');
    return UE.getEditor(id);
}

function upload(array){
    for(var i=0; i<array.length; i++){
        UE.getEditor("container").execCommand('inserthtml','<p><img src="'+array[i]+'"></p>', true);
    }

    layui.use("layer",function (){ layer.closeAll(); });
}