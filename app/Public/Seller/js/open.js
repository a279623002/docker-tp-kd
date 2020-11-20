/*
*iframe弹出层
*
*CopyRight 2016 sz
*
*插件是基于jquery-1.8.3.js
*
*Date 2016-03-21 9:53 20
*
*/
jQuery.openWin = function (height, width, url) {
    if ($("#ow002").length > 0) {
        return false;
    }
    //1.创建透明层
    //2.创建主内容层
    //3.创建iframe
    var scrollH = $(document).scrollTop();
    var scrollL = $(document).scrollLeft();
    var topVal = ($(window).height() - height) / 2 + scrollH;
    var leftVal = ($(window).width() - width) / 2 + scrollL;

    if (topVal < 0) {
        topVal = 10;
    }

    //1.创建透明层
    $("<div id='ow001' class='ow_black_overlay'></div>")
                    .height($(document).height())
                    .width($(document).width())
                    .appendTo($("body"));

    //2.创建主内容层
    //3.创建iframe
    $("<div id='ow002' class='ow_ycgl_tc'></div>")
                    .height(height)
                    .width(width)
                    .css("left", leftVal)
                    .css("top", topVal)
                    .html("<iframe scrolling='no' frameborder='0' src='" + url + "' style='width:100%;height:100%; background:#fff;'></iframe>")
                    .appendTo($("body"));
}

jQuery.closeWin = function () {
    $("#ow002").remove(); //2.删除主内容层
    $("#ow001").remove(); //1.删除透明层
}

/**
*message：内容
*title：标题
*obj：json格式，可以为空，默认值是{ width: 250, height: 50 }
*fun：回调函数
*/
jQuery.openAlter = function (message, title, obj, fun, buttonText) {
    if ($("#ow_alter002").length > 0) {
        return false;
    }
    if (obj == null) {
        obj = { width: 250, height: 50 };
    }

    var closeBtnTxt = buttonText || '关闭';

    //1.创建透明层
    //2.创建主内容层
    var height = obj.height < 210 ? 210 : obj.height;
    var width = obj.width < 350 ? 350 : obj.width;
    var scrollH = $(document).scrollTop();
    var scrollL = $(document).scrollLeft();
    var topVal = ($(window).height() - height) / 2 + scrollH;
    var leftVal = ($(window).width() - width) / 2 + scrollL;
    var aleft = width / 2 - 80/*关闭按钮宽度的一半-20px padding*/;
    if (topVal < 0) {
        topVal = 10;
    }

    var el = "<div class='sjzc_t' id='ow_alter002'><div class='sjzc_1_t' style='color:Red; text-align:center;'>{title}</div><div class='sjzc_2_t'><div class='sjzc_5_t' style='margin-top: 10px; '><div style='overflow:auto'>{message}</div><div class='sjzc_5_t' style='margin-top: 20px;'><ul><li class='sjzc_7_t'><a href='javascript:void(0)' style='margin-left:{aleft}px' id='ow_alter002_close'></a></li></ul></div></div></div></div>";

    el = el.replace(/{title}/, title);
    el = el.replace(/{message}/, message);
    el = el.replace(/{aleft}/, aleft);
    //1.创建透明层
    $("<div id='ow_alter001' class='ow_black_overlay' style='z-index: 1003'></div>")
                    .height($(document).height())
                    .width($(document).width())
                    .appendTo($("body"));


    //2.创建主内容层 
    $(el)
    //.height(height)
                    .width(width)
                    .css("left", leftVal)
                    .css("top", topVal)
                    .appendTo($("body"));

    $("#ow_alter002_close")
        .text(closeBtnTxt)
        .click(function () {
            $.closeAlert();
            if (typeof fun == 'function') {
                fun(); //回调函数
            }
        });
}

jQuery.closeAlert = function () {
    $("#ow_alter002").remove(); //2.删除主内容层
    $("#ow_alter001").remove(); //1.删除透明层
}