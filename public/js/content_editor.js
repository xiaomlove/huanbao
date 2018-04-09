/*
图文编辑器

*/

;(function (window, $) {
    var TextModal = window.TextModal;
    var ImageModal = window.ImageModal;

    var ContentEditor = function (options) {
        var defaults = {
            wrapId: "", //内容包裹容器ID
            content: "", //内容
            uploadUrl: "",//文件上传url
            tokenUrl: "",//获取上传token
            formId: "",//表单ID
            submitBtnSelector: "",//提交按钮选择器
            createdRedirectUrl: "", //创建后提交url
            updatedRedirectUrl: "",//更新后提交url
            contentFieldName: "content",//内容字段名
        }
        var settings = $.extend(true, {}, defaults, options);
        if (!settings.wrapId) {
            throw new Error("缺少wrapId");
        }
        var $wrap = $("#" + settings.wrapId);
        if ($wrap.length == 0) {
            throw new Error("非法wrapId: " + settings.wrapId);
        }
        var commonClassName = "editor-block",
            textClassName = "editor-block-text",
            imageClassName = "editor-block-image";

        var index, activeBlock, action, self = this;

        if (!settings.content) {
            var contentData = [{type: "text", data: {text: "默认内容"}}];
        } else {
            var contentData = JSON.parse(settings.content.replace(/\n/g, '\\n'));
        }

        //插入数据
        function appendData(index, data) {
            contentData.splice(index + 1, 0, data);
        }

        //更新数据
        function updateData(index, data) {
            contentData.splice(index, 1, data);
        }

        //删除数据
        function removeData(index) {
            contentData.splice(index, 1);
        }

        //获取数据
        function getData(index) {
            return contentData.slice(index, index + 1)[0];
        }

        //添加文本
        var textModal = new TextModal({
            onSubmit: function (text) {
                var textToShow = text.replace(/[\r\n]/g, "<br/>");
                var data = {type: "text", data: {text: text}};
                var dataToShow = {text: textToShow, classList: [commonClassName, textClassName]};
                if (action == "add") {
                    appendData(index, data);
                    activeBlock.after(textModal.createBlock(dataToShow));
                } else if (action == "edit") {
                    updateData(index, data);
                    activeBlock.html(textToShow);
                }
            }
        });
        //添加图片
        var imageModal = new ImageModal({
            uploadUrl: settings.uploadUrl,
            tokenUrl: settings.tokenUrl,
            afterUpload: function (response) {
                var data = {type: "image", data: response};
                var dataToShow = {key: response.key, classList: [commonClassName, imageClassName, "text-center"]};
                if (action == "add") {
                    appendData(index, data);
                    activeBlock.after(imageModal.createBlock(dataToShow));
                } else if (action == "edit") {
                    updateData(index, data);
                    activeBlock.find("img").attr("src", dataToShow.url);
                }
            }
        });


        //初始化内容
        $.each(contentData, function (index, item) {
            if (item.type == "text") {
                var data = item.data;
                if (!data.classList) {
                    data.classList = [commonClassName, textClassName];
                }
                $wrap.append(textModal.createBlock(data));
            } else if (item.type == "image") {
                var data = item.data;
                if (!data.classList) {
                    data.classList = [commonClassName, imageClassName];
                }
                $wrap.append(imageModal.createBlock(data));
            }
        });

        //工具条
        var $toolBarWrap = $('<div>').css({
            position: "absolute",
            display: "none",
            backgroundColor: "lightgreen",
        }).appendTo($("body"));
        //添加文本
        var $toolBarAddText = $('<a style="cursor: pointer;z-index: 9999;margin: 5px"><i class="fa fa-plus" aria-hidden="true"></i>文本</a>').appendTo($toolBarWrap);
        $toolBarAddText.on("click", function () {
            action = "add";
            textModal.show();
        })
        //添加图片
        var $toolBarAddImage = $('<a style="cursor: pointer;z-index: 9999;margin: 5px"><i class="fa fa-plus" aria-hidden="true"></i>图片</a>').appendTo($toolBarWrap);
        $toolBarAddImage.on("click", function () {
            action = "add";
            imageModal.show();
        })
        //编辑
        var $toolBarEdit = $('<a style="cursor: pointer;z-index: 9999;margin: 5px"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>编辑</a>').appendTo($toolBarWrap);
        $toolBarEdit.on("click", function () {
            action = "edit";
            var data = getData(index);
            if (activeBlock.hasClass(textClassName)) {
                textModal.show(data.data);
            } else if (activeBlock.hasClass(imageClassName)) {
                imageModal.show(data.data);
            }
        })
        //删除
        var $toolBarRemove = $('<a style="cursor: pointer;z-index: 9999;margin: 5px"><i class="fa fa-times" aria-hidden="true"></i>删除</a>').appendTo($toolBarWrap);
        $toolBarRemove.on("click", function () {
            if (contentData.length <= 1) {
                alert("内容不要为空");
                return;
            }
            removeData(index);
            activeBlock.remove();
            $toolBarWrap.hide();
        })

        $wrap.on("mouseenter", "." + commonClassName, function () {
            var $this = $(this), offset = $this.offset();
            index = $this.index();
            activeBlock = $this;
            $toolBarWrap.css({
                display: "block",
                left: offset.left + $this.width() - $toolBarWrap.width(),
                top: offset.top
            });
        })

        //封装表单提交部分
        if (settings.formId && settings.submitBtnSelector) {
            var $form = $("#" + settings.formId);
            $form.on("click", settings.submitBtnSelector, function (e) {
                var data = $form.serialize();
                data += "&" + settings.contentFieldName + "=" + JSON.stringify(contentData);
                $.ajax({
                    url: $form.attr("action"),
                    type: "post",
                    dataType: "json",
                    data: data,
                }).done(function (response) {
                    console.log(response);
                    alert(response.msg);
                    if (response.ret == 0) {
                        var $method = $("[name=_method]");
                        if ($method.length && $.inArray($method.val().toLowerCase(), ['patch', 'put']) > -1 && settings.updatedRedirectUrl) {
                            window.location.href = settings.updatedRedirectUrl;
                        } else if (settings.createdRedirectUrl) {
                            window.location.href = settings.createdRedirectUrl;
                        }
                    } else {
                        alert(response.msg);
                    }
                }).fail(function (xhr, errstr, errThrown) {
                    var response = xhr.responseJSON;
                    var msg = "";
                    for (var i in response.data.errors) {
                        msg += response.data.errors[i][0] + "\n";
                    }
                    alert(msg);
                }).always(function () {

                })
            })
        }

        this.getData = function () {
            return contentData;
        }

    }
    window.ContentEditor = ContentEditor;
})(window, jQuery);