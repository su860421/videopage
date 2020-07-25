function addvideo(returnmsg) {
    var item = '' +
        '<div class="video-container col-md-3 col-sm-6 col-xs-12" style="margin-top:25px;" id=' + returnmsg.id + '>' +
        '<div class="card" style="width: 18rem;" >' +
        '<button type="button" onclick=changepage("' + returnmsg.videokey + '")><img src="https://videoblogdat.s3-ap-northeast-1.amazonaws.com/' + returnmsg.imgkey + '"' +
        'id=' + returnmsg.videokey + ' class="card-img-top" width="240px" height="200px" alt="圖片失效"></button>' +
        '<div class="card-body">' +
        '<p name:"title" class="card-text" Align="Center" style="font-size:150%;">' + returnmsg.title + '</p>' +
        '<button type="button" class="btn btn-secondary" onclick=del("' + returnmsg.videokey + '")>刪除該影片</button>' +
        '</div>' +
        '</div>' +
        '</div>';
    $('#showownvideo').append(item);
}

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$.ajax({ //---------------------------------------------------初始新增影片
        url: "/ownvideodat",
        type: "get",
        dataType: "json",
    })
    .done(function(data) {
        for (var i = 0; i < data.length; i++) {
            // 丟給 render function
            addvideo(data[i]);
        }
    })
    .fail(function(err) {
        alert("错誤");
    })

function Logout(item) {
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

function del(videourl) {//--------------------------------------------刪除
    $.ajax({
            url: "/delet",
            type: "post",
            dataType: "json",
            data: {
                videokey: videourl
            }
        })
        .done(function(data) {
            var el = document.getElementById(data.id);
            el.remove();
            alert("刪除成功");
        }).fail(function(err) {
            alert("错誤");
        })
}

function changepage(videourl) { //-------------------------------轉址
    // videourl="+https://videoblogdat.s3-ap-northeast-1.amazonaws.com/+"
    // console.log(videourl)
    document.location.href = "/videopage?id=" + videourl;
}
$(document).ready(function() { //------------------------------------------------新增
    $("#submitmsg").click(function() {
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
                if(!returnmsg.status){
                  addvideo(returnmsg);
                  $("input").val("");
                  $("textarea").val("");
                  alert(returnmsg.msg);
                }else{
                  alert(returnmsg.msg);
                }
            })
            .fail(function(err) {
                alert("错誤");
            })

    })
});

function updat(item) { //---------------------------------------------------modal互動視窗顯示
    $('#myModal').modal('show')
}
