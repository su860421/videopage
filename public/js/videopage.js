$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
videoadd = window.location.search; //取得網址
videoadd = videoadd.split('='); //將影片連結取出
// console.log(videoadd)

//-------------------------websocket測試
//Pusher.logToConsole = true; //pusher日誌顯示

var pusher = new Pusher('2136ac7bcdd5797f3527', { //設定密碼位置等資訊
    cluster: 'ap3', //位置
    forceTLS: true
});

var channel = pusher.subscribe('testChannel'); //設定聊天室是哪個
channel.bind('form-submitted', function(data) { //收到後執行那些動作
    addmsg(data); //將資料show出來
    //alert(JSON.stringify(data));
});
var channel = pusher.subscribe('updatchannel'); //設定聊天室是哪個
channel.bind('updat-submitted', function(returnmsg) { //收到後執行那些動作
    var el = returnmsg.id;
    document.getElementById('show' + el).innerHTML = ' 帳號 :' + returnmsg.email + ' , 更新時間 ：' + returnmsg.updated_at + '<br><br>' + ' 標題 : ' + returnmsg.title;
    document.getElementById('msg' + el).innerHTML = returnmsg.msg;
});
var channel = pusher.subscribe('delchannel'); //設定聊天室是哪個
channel.bind('del-submitted', function(item) { //收到後執行那些動作
    var el = document.getElementById(item.id);
    //console.log(item);
    el.remove();

});
//-------------------------websocket測試

//-------------------------卷軸條到底產生其他留言
var nextItem = 21;
$(window).scroll(function() {
    // Returns height of browser viewport
    var window_height = $(window).height();

    var window_scrollTop = $(window).scrollTop();

    // Returns height of HTML document
    var document_height = $(document).height();

    if (window_height + window_scrollTop == document_height) {
        nextItem = nextItem + 20;
        // var loadMore = function() {
        //     for (var i = 0; i < 20; i++) {
        //         var item = document.createElement('li');
        //         item.innerText = 'Item ' + nextItem++;
        //         listElm.appendChild(item);
        //     }
        // }
    }
});
//-------------------------卷軸條到底產生其他留言
$.ajax({ //-------------------------------------------影片生成
        url: "/videoinfor",
        type: "post",
        dataType: "json",
        data: {
            videokey: videoadd[1]
        }
    })
    .done(function(returnmsg) {
        var item = '' + //生成影片區塊
            '<video controls width="90%">' +
            '<source src="https://videoblogdat.s3-ap-northeast-1.amazonaws.com/' + returnmsg.videokey + '" type="video/mp4">' +
            '</video>' +
            '<h3>' + returnmsg.title + '</h3>' +
            '<p>' + returnmsg.msg + '</p>';
        $('#vidoplay').append(item);
    })
    .fail(function(err) {
        alert("错誤");
    })
$.ajax({ //-----------------------------------------------------------留言生成
        url: "/videomsg",
        type: "post",
        dataType: "json",
        data: {
            videokey: videoadd[1]
        }
    })
    .done(function(data) {
        for (let value of data) {
            // 丟給 render function
            addPost(value);
        }
    })
    .fail(function(err) {
        alert("错誤");
    })

function addPost(returnmsg) {
    var item = '' +
        '<div class="panel panel-default" id=' + returnmsg.id + '>' +
        '<div class="panel-heading">' +
        '<h3 class="panel-title" id="show' + returnmsg.id + '">' + ' 帳號 : ' + returnmsg.email + '  , 更新時間 ：' + returnmsg.updated_at + '<br><br>' +
        ' 標題 : ' + returnmsg.title +
        '</h3>' +
        '<button type="button" class="btn btn-success pull-right" style="margin-top:-45px;" onclick="updat(' + returnmsg.id + ');">' + '編輯' + '</button>' +
        '<button type="button" class="btn btn-danger pull-right"style="margin-right:80px;margin-top:-45px;" onclick="del(' + returnmsg.id + ');">' + '刪除' + '</button>' +
        '</div>' +
        '<div class="panel-body">' +
        '<p id="msg' + returnmsg.id + '">' + returnmsg.msg + '</p>'
    '</div>' +
    '</div>';
    $('#msg').append(item);
}

function addmsg(data) {
    var item = '' +
        '<div class="panel panel-default" id=' + data.id + '>' +
        '<div class="panel-heading">' +
        '<h3 class="panel-title" id="show' + data.id + '">' + ' 帳號 : ' + data.email + '  , 更新時間 ：' + data.time + '<br><br>' +
        ' 標題 : ' + data.title +
        '</h3>' +
        '<button type="button" class="btn btn-success pull-right" style="margin-top:-45px;" onclick="updat(' + data.id + ');">' + '編輯' + '</button>' +
        '<button type="button" class="btn btn-danger pull-right"style="margin-right:80px;margin-top:-45px;" onclick="del(' + data.id + ');">' + '刪除' + '</button>' +
        '</div>' +
        '<div class="panel-body">' +
        '<p id="msg' + data.id + '">' + data.msg + '</p>' +
        '</div>' +
        '</div>';
    $('#msg').append(item);
}

function logout(item) { //-----------------------------------------------登出
    $.ajax({
            url: "/logout",
            type: "get",
            dataType: "json",

        })
        .done(function(returnmsg) {
            location.href = "/login";
        })
        .fail(function(err) {
            location.href = "/login";
            alert("已登出");
        })
}
$(document).ready(function() { //------------------------------------------------新增留言
    $("#submitmsg").click(function() {
        $.ajax({
                url: "/msgsubmit",
                type: "post",
                dataType: "json",
                data: {
                    title: $("#leavetitle").val(),
                    msg: $("#leavemsg").val(),
                    videokey: videoadd[1]
                }
            })
            .done(function(data) {
                if (data.id) {
                    // document.getElementById("newmsg").reset();
                    // $("#newmsg")[0].reset();
                    $("input").val("");
                    $("textarea").val("");

                } else { //否則讀取後端回傳 json 資料 errorMsg 顯示錯誤訊息
                    $("#newmsg")[0].reset(); //重設 ID 為 demo 的 form (表單)
                    $("#result").html('<font color="#ff0000">' + data.errorMsg + '</font>');
                }
            })
            .fail(function(err) {
                alert("错誤");
            })
    })
    $("#modify").click(function() { //----------------------------------------------------------修改
        var id = $(this).attr("name");
        $.ajax({
                type: "PUT", //傳送方式
                url: '/updatemsg', //傳送目的地
                dataType: "json", //資料格式
                data: { //傳送資料
                    id: id,
                    title: $("#modaltitle").val(),
                    msg: $("#modalmsg").val()
                }
            })
            .done(function(returnmsg) {
                if (!returnmsg.id) {
                    alert(returnmsg.msg);
                } else {
                    $("#modifymsg").find(":text,textarea").each(function() { //修改後文字還在上面尚未解決
                        $(this).val("");
                    });
                }
            })
            .fail(function(err) {
                alert("错誤");
            })
    })
    $("#submitvideo").click(function() { //------------------------------------------------新增影片
        var video_data = $('#uploadvideo').prop('files')[0]; //取得上傳檔案屬性
        var form_data = new FormData(); //建構new FormData()
        var img_data = $('#uploadimg').prop('files')[0]; //取得上傳檔案屬性
        form_data.append('file', video_data); //把物件加到file後面
        form_data.append('imgfile', img_data);
        form_data.append('title', $("#title").val());
        form_data.append('msg', $("#videodescription").val());
        $.ajax({
                type: "POST", //傳送方式
                url: '/upload', //傳送目的地
                dataType: "json", //資料格式
                cache: false,
                contentType: false,
                processData: false,
                data: form_data
            })
            .done(function(returnmsg) {
                $("input").val("");
                $("textarea").val("");
                alert(returnmsg.msg);
            })
            .fail(function(err) {
                alert("错誤");
            })
    })
});

function newvideo(item) { //---------------------------------------------------modal互動視窗顯示
    $('#myModal').modal('show')
}

function updat(item) { //---------------------------------------------浮動視窗
    $.ajax({
            url: "/updat",
            type: "post",
            dataType: "json",
            data: {
                id: item
            }
        })
        .done(function(update) {
            if (!update.id) {
                alert(update.msg);
            } else {
                document.getElementById('modify').name = update.id;
                document.getElementById('modalmsg').placeholder = update.msg;
                document.getElementById('modaltitle').placeholder = update.title;
                $('#updatModal').modal('show')
            }
        })
        .fail(function(err) {
            alert("错誤");
        })
}

function del(item) { //---------------------------------------------------msg刪除
    $.ajax({
            url: "/deletemsg",
            type: "post",
            dataType: "json",
            data: {
                id: item
            }
        })
        .done(function(item) {
            if (!item.id) {
                alert(item.msg);
            } else {
                alert("刪除成功");
            }
        })
        .fail(function(err) {
            alert("错誤");
        })
}
