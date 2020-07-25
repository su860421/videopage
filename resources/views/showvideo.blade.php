<!DOCTYPE html>
<html>
  <head>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <script src="https://js.pusher.com/3.2/pusher.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script><!--必填-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
    <script src="http://cdn.bootcss.com/bootstrap/2.3.1/js/bootstrap-modal.js"></script>
  </head>
  <body>
    <nav class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand" href="/" style="font-weight:bolder">影片網站</a>
          </div>
          <div class="btn-group pull-right btn-group-lg" role="group" aria-label="Basic example">
            <button type="button" class="btn btn-secondary" onclick="newvideo('1');">新增影片</button>
            <button type="button" class="btn btn-secondary" value="yourvideo" onclick="location.href='/'">你的帳戶</button>
            <button type="button" class="btn btn-secondary" onclick="logout('1');">登出</button>
          </div>
        </div>
    </nav>

    <div class="newvid">
      <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <h4 class="modal-title" id="myModalLabel">新增一個影片</h4>
              </div>
              <form class="bs-example bs-example-form" role="form" id="videonew">
                <div class="input-group" style="margin-left:20px;margin-top:5px;">
                  <h4 style="font-weight:bold;">新增影片</h4>
                  <input type="file"  name="videofile" class="form-control" id="uploadvideo" accept=".mp4, .avi, .mov"></br>
                </div>
                <div class="input-group" style="margin-left:20px;margin-top:5px;">
                  <h4 style="font-weight:bold;">新增縮圖</h4>
                  <input type="file"  name="imgfile" class="form-control" id="uploadimg" accept=".png, .jpg, .jpeg"></br>
                </div>
                <div class="input-group" style="margin-left:20px;">
                   <input type="text" class="form-control" placeholder="影片標題" name="title" id="title" style="margin-top:10px;"></br>
                </div>
                <div class="input-group" style="margin-left:20px;">
                  <textarea type="text" class="form-control" placeholder="影片介紹" name="videodescription" id="videodescription" style="margin-top:10px;"></textarea></br>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="margin-top:20px;">關閉</button>
                    <button type="button" class="btn btn-dark" id="submitvideo" style="margin-top:20px;">新增該影片</button>
                </div>
              </form>
            </div>
        </div>
      </div>
    </div>
    <div class="updatmsg">
      <div class="modal fade" id="updatModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <h4 class="modal-title" id="myModalLabel">修改該筆資料</h4>
              </div>
              <form class="bs-example bs-example-form" role="form" id="modifymsg">
                <div class="input-group">
                  <input type="text" class="form-control" placeholder="" id="modaltitle" style="margin-left:10px;"></br>
                </div>
                <div class="input-group">
                  <textarea type="text" class="form-control input-group-lg" placeholder="" id="modalmsg" style="margin-top:10px;margin-left:10px;"></textarea></br>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
                    <button type="button" class="btn btn-primary" id="modify" name="" data-dismiss="modal">修改</button>
                </div>
              </form>
            </div>
        </div>
      </div>
    </div>

    <div class="video-container col-md-8 col-sm-12 col-xs-12"style="margin-top:2%;margin-left:3%;">
      <!--show video-->
      <div id="vidoplay"></div>
      <!--show msg-->
      <div id="msg"></div>
      <!--submit msg-->
      <div style="padding: 10px 10px 10px;">
  	     <form class="bs-example bs-example-form" role="form" id="newmsg">
           {{ csrf_field() }}
             <div class="input-group">
               <input type="text" class="form-control" placeholder="標題" id="leavetitle" style="margin-top:10px;"></br>
             </div>
             <div class="input-group">
               <textarea type="text" class="form-control" placeholder="內文" id="leavemsg" style="margin-top:10px;"></textarea></br>
             </div>
             <button type="button" class="btn btn-dark" id="submitmsg" style="margin-top:20px;">新增該留言</button>
         </form>
      </div>
    </div>



    <div class="video-container col-md-3 col-sm-12 col-xs-12" style="margin-top:2%;margin-left:3%;" >
      <div class="card" style="width: 18rem;" >
        <img src="https://img.lovepik.com/photo/50092/5390.jpg_wh860.jpg" class="card-img-top" width="150%" alt="圖片失效">
          <div class="card-body">
            <p name:"title" class="card-text">測試測試測試測試123456789</p>
          </div>
      </div>
    </div>
    <script src="js/videopage.js" type='text/javascript'></script>
  </body>
</html>
