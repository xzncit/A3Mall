/**
 * @Name ${name}
 * @Author: ${author}
 * @License: ${license}
 * @Version: ${version}
 */
layui.define(['jquery', 'laytpl'], function (exports){
    "use strict";


    var $           = layui.jquery || layui.$,
        laytpl      = layui.laytpl,
        $body       = $(window.document.body),

        // === 内部事件模块, 由于layui不让同一事件注册多次监听了，故此处自己实现。 ===>
        EVENT       = {
            DROPDOWN_SHOW: "a"
        },
        // 内部事件。目前仅仅有一个打开下拉框事件。此事件不暴露给开发者，仅作为内部使用。
        INNER_EVENT = {},

        // 监听指定事件。
        onEvent     = function (event, cb) {
            var evnts = INNER_EVENT[event] || [];
            evnts.push(cb);

            INNER_EVENT[event] = evnts;
        },

        // 发出事件
        makeEvent   = function(event, param) {
            var evnts = INNER_EVENT[event] || [];
            $.each(evnts, function (index, value) {
                value(param);
            });
        },
        // <=== 内部事件模块 ===

        // 允许通过为 window 设置 MICROANSWER_DROPDOWAN 变量来改变本组件的注册名。
        // 以避免将来 layui 官方加入下拉控件与本控件重名时，可让本控件依然能正常运行
        // 在另一个名称上。
        MOD_NAME = window.MICROANSWER_DROPDOWAN || "dropdown",

        // 小箭头模板
        MENUS_POINTER_TEMPLATE = "{{# if (d.arrow){ }}<div class='dropdown-pointer'></div>{{# } }}",
        MENUS_TEMPLATE_START = "<div tabindex='0' " +
            "class='layui-anim layui-anim-upbit dropdown-root' " + MOD_NAME + "-id='{{d.downid}}' " +
            "style='z-index: {{d.zIndex}}'>" +
            MENUS_POINTER_TEMPLATE +
            "<div class='dropdown-content' " +
            "style='margin: {{d.gap}}px {{d.gap}}px;" +
            "background-color: {{d.backgroundColor}};" +
            "min-width: {{d.minWidth}}px;" +
            "min-height: {{d.minHeight}}px;" +
            "max-height: {{d.maxHeight}}px;" +
            "overflow: auto;'>",
        MENUS_TEMPLATE_END = "</div></div>",


        // 菜单项目模板。
        MENUS_TEMPLATE =
            MENUS_TEMPLATE_START +
                "{{# layui.each(d.menus, function(index, item){ }}" +
                    "{{# if ('hr' === item) { }}" +
                        "<hr>" +
                    "{{# } else if (item.header) { }}" +
                        "{{# if (item.withLine) { }}" +
                            "<fieldset class=\"layui-elem-field layui-field-title menu-header\" style=\"margin-left:0;margin-bottom: 0;margin-right: 0\">" +
                                "<legend>{{item.header}}</legend>" +
                            "</fieldset>" +
                        "{{# } else { }}" +
                            "<div class='menu-header' style='text-align: {{item.align||\'left\'}}'>{{item.header}}</div>" +
                        "{{# } }}" +
                    "{{# } else { }}" +
                        "<div class='menu-item'>" +
                            "<a href='javascript:;' lay-event='{{item.event}}'>" +
                                "{{# if (item.layIcon){ }}" +
                                    "<i class='layui-icon {{item.layIcon}}'></i>&nbsp;" +
                                "{{# } }}" +
                                "<span>{{item.txt}}</span>" +
                            "</a>" +
                        "</div>" +
                    "{{# } }}" +
                "{{# }); }}" +
            MENUS_TEMPLATE_END,


        // 默认配置。
        DEFAULT_OPTION = {

            // 打开下拉框的触发方式
            // 可填: click, hover
            showBy: 'click',

            // 左对齐。可选值: left, center, right
            align: "left",

            // 最小宽度
            minWidth: 80,

            minHeight: 10,

            // 最大高度
            maxHeight: 300,

            zIndex: 891,

            // 下拉框和触发按钮的间隙
            gap: 8,

            // 隐藏事件
            onHide: function ($dom, $down) {},

            // 显示事件
            onShow: function ($dom, $down) {},

            // 滚动界面的时候，如果下拉框是显示的，则将隐藏，如果值为 follow 则不会隐藏。
            scrollBehavior: "follow",

            // 下拉内容背景颜色
            backgroundColor: "#FFF",

            // 默认css地址，允许通过配置指定其它地址
            cssLink: "https://cdn.jsdelivr.net/gh/microanswer/layui_dropdown@${version}/dist/dropdown.css",

            // 初始化完成后是否立即显示下拉框。
            immed: false,

            // 是否显示小箭头
            arrow: true,
        },

        /**
         * 下拉菜单本体定义类。
         *
         * @Param $dom 可以是这些内容: jquery对象、选择器。
         */
        Dropdown = function($dom, option) {

            /*
            * 在实现逻辑中使用了一些字段挂载在 Dropdown 上，这里统一做一个介绍：
            * this.$dom:   表示触发器的jquery对象。
            * this.$down:  表示下拉框下拉部分的jquery对象。在init方法里初始化。
            * this.option: 表示选项配置。
            * this.opened: 表示下拉框是否展开。
            * this.fcd:    表示当前是否处于有焦点状态。
            * this.mic:    表示鼠标是否在组件范围内， 鼠标在 触发器+下拉框 里即算是在组件范围内。mic = mouseInComponent
            * */

            if (typeof $dom === "string") {$dom = $($dom);}
            this.$dom = $dom;
            this.option = $.extend({
                downid: String(Math.random()).split('.')[1],
                filter: $dom.attr("lay-filter")
            }, DEFAULT_OPTION, option);

            if (this.option.gap > 20) {
                this.option.gap = 20;
            }

            this.init();
        };

        // 加载css，使外部不需要手动引入css。允许通过设置 window.dropdown_cssLink 来修改默认css地址。
        //layui.link(window[MOD_NAME+"_cssLink"] || DEFAULT_OPTION.cssLink, function () {/*ignore*/}, MOD_NAME + "_css");

        // 初始化下拉菜单。
        Dropdown.prototype.init = function () {
            var _this = this;

            if (_this.option.menus && _this.option.menus.length > 0) {
                laytpl(MENUS_TEMPLATE).render(_this.option, function (html) {
                    _this.$down = $(html);
                    _this.$dom.after(_this.$down);
                    _this.initSize();
                    _this.initEvent();

                    _this.onSuccess();
                });
            } else if (_this.option.template) {
                var templateId;
                if (_this.option.template.indexOf("#") === -1) {
                    templateId = "#" + _this.option.template;
                } else {
                    templateId = _this.option.template;
                }

                var data = $.extend($.extend({}, _this.option), _this.option.data || {});
                laytpl(MENUS_TEMPLATE_START + $(templateId).html() + MENUS_TEMPLATE_END).render(data, function (html) {
                    _this.$down = $(html);
                    _this.$dom.after(_this.$down);
                    _this.initSize();
                    _this.initEvent();

                    _this.onSuccess();
                });

            } else {
                layui.hint().error("下拉框目前即没配置菜单项，也没配置下拉模板。[#" + (_this.$dom.attr("id")||"") + ",filter="+_this.option.filter + "]");
            }
        };

        Dropdown.prototype.initSize = function () {
            this.$down.find(".dropdown-pointer").css("width", this.option.gap * 2);
            this.$down.find(".dropdown-pointer").css("height", this.option.gap * 2);
        };

        // 初始化位置信息
        Dropdown.prototype.initPosition = function() {
            var btnOffset = this.$dom.offset();
            var btnHeight = this.$dom.outerHeight();
            var btnWidth  = this.$dom.outerWidth();
            var btnLeft = btnOffset.left;
            var btnTop  = btnOffset.top - window.pageYOffset;

            var downHeight = this.$down.outerHeight();
            var downWidth = this.$down.outerWidth();

            var downLeft;
            var downTop;
            var pointerLeft;  // 箭头左边偏移量
            var pointerTop; // 箭头右边偏移量
            if (this.option.align === 'right') {
                downLeft = (btnLeft + btnWidth) - downWidth + this.option.gap;
                pointerLeft = - (Math.min(downWidth - (this.option.gap * 2), btnWidth) / 2);
            } else if (this.option.align === 'center') {
                downLeft = btnLeft + ((btnWidth - downWidth) / 2);
                pointerLeft =  (downWidth - (this.option.gap * 2)) / 2;
            } else {
                downLeft = btnLeft - this.option.gap;
                pointerLeft = Math.min(downWidth - (this.option.gap * 2), btnWidth) / 2;
            }
            downTop = btnHeight + btnTop;// + this.option.gap;

            var pt = this.$arrowDom || (this.$arrowDom = this.$down.find(".dropdown-pointer"));
            // var pointerHeigt = Math.pow(this.option.gap, 2) / Math.sqrt(Math.pow(this.option.gap, 2)*2);
            pointerTop = -this.option.gap;

            if (pointerLeft > 0) {
                pt.css("left", pointerLeft);
                pt.css("right", "unset");
            } else {
                pt.css("left", "unset");
                pt.css("right", (-1 * pointerLeft));
            }
            // 检测是否超出浏览器边缘
            if (downLeft + downWidth >= window.innerWidth) {
                downLeft = window.innerWidth - downWidth + this.option.gap;
            }
            if (downTop + downHeight >= window.innerHeight) {
                downTop = btnTop - downHeight;// - this.option.gap;
                pointerTop = downHeight - (this.option.gap); //(pointerHeigt * 2) - 1;

                pt.css("top", pointerTop).addClass("bottom");
            } else {
                pt.css("top", pointerTop).removeClass("bottom");
            }


            this.$down.css("left", downLeft);
            this.$down.css("top", downTop);
        };

        // 显示下拉内容
        Dropdown.prototype.show = function () {
            var _this = this;

            _this.initPosition();

            _this.opening = true; // 引入这个字段用于确保在动画过程中鼠标移除组件区域时不会隐藏下拉框。
            // 使用settimeout原因:
            // 如果 这个show方法在某个点击事件里面调用，那么立即调用focus方法的话是不会生效的。
            // 为了稳妥起见，延时100毫秒，再使下拉框获取焦点。从而在其失去焦点时能够自动隐藏。
            setTimeout(function () {
                _this.$down.focus();
            }, 100);

            _this.$down.addClass("layui-show");
            _this.opened = true;

            // 发出通知，告诉其他dropdown，我打开了，你们自己看情况办事!
            makeEvent(EVENT.DROPDOWN_SHOW, _this);

            // 调起回调。
            _this.option.onShow && _this.option.onShow(_this.$dom, _this.$down);
        };

        // 隐藏下拉内容
        Dropdown.prototype.hide = function () {
            this.fcd = false;
            this.$down.removeClass("layui-show");
            this.opened = false;

            this.option.onHide && this.option.onHide(this.$dom, this.$down);
        };

        // 当可以条件允许隐藏时，进行隐藏。
        // 条件：鼠标在下拉框范围外、下拉框和触发按钮都没有焦点
        Dropdown.prototype.hideWhenCan = function () {
            if (this.mic) {
                return;
            }
            if (this.opening) {
                return;
            }
            if (this.fcd) {
                return;
            }
            this.hide();
        };

        // 显示/隐藏下拉内容
        Dropdown.prototype.toggle = function () {
            if (this.opened) {
                this.hide();
            } else {
                this.show();
            }
        };

        Dropdown.prototype.onSuccess = function () {

            // 调起回调。
            this.option.success && this.option.success(this.$down);

            // 如果配置了立即显示，这里进行显示。
            if (this.option.immed) {
                this.show();
            }
        };


        // 滚动界面时此方法会执行
        Dropdown.prototype._onScroll = function() {
            var _this = this;

            // 如果下拉框不是展开状态，不用执行这些逻辑。
            // OHHHHHH! md才发现，当界面上出现很多下拉框(很少情况)，这个判断真的极大提高了性能，避免了无用的执行。
            // 。使页面滚动不卡顿了，尤其是在ie里。
            if (!_this.opened) {
                return;
            }

            if (this.option.scrollBehavior === 'follow') {
                setTimeout(function () {
                    _this.initPosition();
                }, 1);
            } else {
                this.hide();
            }
        };

        // 初始化事件。
        Dropdown.prototype.initEvent = function () {
            var _this = this;

            // 全局仅允许同时开启一个下拉菜单。所以这里注册一个监听。
            // 如果打开的下拉菜单不是我本身，则我应该隐藏自己。
            onEvent(EVENT.DROPDOWN_SHOW, function (dropdown) {
                if (dropdown !== _this) {
                    _this.hide();
                }
            });

            _this.$dom.mouseenter(function () {
                _this.mic = true;
                if (_this.option.showBy === 'hover') {
                    _this.fcd = true;
                    _this.$down.focus();
                    _this.show();
                }
            });
            _this.$dom.mouseleave(function () {
                _this.mic = false;
            });
            _this.$down.mouseenter(function () {
                _this.mic = true;
                _this.$down.focus();
            });
            _this.$down.mouseleave(function () {
                _this.mic = false;
            });

            if (_this.option.showBy === 'click') {
                _this.$dom.on("click", function () {
                    _this.fcd = true;
                    _this.toggle();
                });
            }

           /* 现通过失焦来保证下拉框隐藏，就不用这块了 $body.on("click", function () {
                if (!_this.mic) {
                    _this.fcd = false;
                    _this.hideWhenCan();
                }
            });*/

            $(window).on("scroll", function () {_this._onScroll();});
            _this.$dom.parents().on("scroll", function () {_this._onScroll();});
            $(window).on("resize", function () {
                if (!_this.opened) {
                    return;
                }
                _this.initPosition();
            });

            _this.$dom.on("blur", function () {
                _this.fcd = false;
                _this.hideWhenCan();
            });
            _this.$down.on("blur", function () {
                _this.fcd = false;
                _this.hideWhenCan();
            });

            // 当下拉框获取焦点时，必然下拉框显示了，这时 吧 opening 设置false
            _this.$down.on("focus", function () {
                _this.opening = false;
            });

            // 点击下拉菜单里的条目事件
            if (_this.option.menus) {
                var $md = $("[" + MOD_NAME + "-id='" + _this.option.downid + "']");

                $md.on("click", "a", function () {
                    var event = ($(this).attr('lay-event') || '').trim();
                    if (event) {
                        layui.event.call(this, MOD_NAME, MOD_NAME + '(' + _this.option.filter + ')', event);
                        _this.hide();
                    } else {
                        layui.hint().error("菜单条目[" + this.outerHTML + "]未设置event。");
                    }
                });
            }
        };

        // 监听事件方法
        function onFilter(layFitler, cb) {
            layui.onevent(MOD_NAME, MOD_NAME + "(" + layFitler + ")", function (event) {
                cb && cb(event);
            });
        }

        // 全局初始化方法。
        function suite(sector, option) {
            // 初始化页面上已有的下拉控件。
            $(sector || "[lay-"+ MOD_NAME +"]").each(function () {
                var $this = $(this);
                var attrOption = new Function('return '+ ($this.attr("lay-" + MOD_NAME) || "{}"))();
                $this.removeAttr("lay-" + MOD_NAME); // 移除节点上的这个标签，因为它很长，不利于调试。
                var dp = $this.data(MOD_NAME) || new Dropdown($this, $.extend({}, attrOption, option || {}));
                $this.data(MOD_NAME, dp);
            });
        }

        // 执行一次，立马让界面上的dropdown乖乖听话。
        suite();

    exports(MOD_NAME, {

        /**
         * 方便手动对界面上的按钮进行初始化
         */
        suite: suite,

        /**
         * 监听menu菜单点击事件
         */
        onFilter: onFilter,

        /**
         * 传入选择器，将其对应的下拉框隐藏。
         * 这个方法常常用代码调用。它不被设计为某个按钮点击后执行这个方法。
         * 因为下拉框的隐藏会在失去focus时自动隐藏，无论点击哪个按钮都会使
         * 下拉框失去focus而隐藏，此方法调用也没意义了。
         * @param {String} sector
         */
        hide: function (sector) {
            // 隐藏指定下拉框。
            $(sector).each(function () {
                var $this = $(this);
                var dp = $this.data(MOD_NAME);
                if (dp) {
                    dp.hide();
                }
            });
        },
        /**
         * 传入选择器，将其对应的下拉框显示。
         *
         * 注意:如果选择器对应的dom没有进行下拉初始化，则此方法会进行初始化。此时会用到参数option，你可以
         * 通过第二个参数传入。但是通常建议传入的选择器对应的dom是经过了下拉框初始化的。
         * @param sector
         * @param option
         */
        show: function (sector, option) {
            // 显示指定下拉框。
            $(sector).each(function () {
                var $this = $(this);
                var dp = $this.data(MOD_NAME);
                if (dp) {
                    dp.show();
                } else {
                    layui.hint().error("警告：尝试在选择器【" + sector + "】上进行下拉框show操作，但此选择器对应的dom并没有初始化下拉框。");
                    // 尝试在一个没有初始化下拉框的dom上调用show方法，这里立即进行初始化。
                    option = option || {};

                    // 立即显示。
                    option.immed = true;
                    suite(sector, option);
                }
            });
        },
        version: "${version}"
    });
});
