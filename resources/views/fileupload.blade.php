<!DOCTYPE html>
<html>
  <head>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="http://cdn.bootcss.com/bootstrap/2.3.1/js/bootstrap-modal.js"></script>
  </head>
  <body>
    <div style="padding: 10px 10px 10px;">
	     <form class="bs-example bs-example-form" role="form" id="newvideo">
            {{ csrf_field() }}
            <div class="input-group">
              <input type="file"  name="file" class="form-control" id="uploadvideo" ></br>
            </div>
            <div class="input-group">
               <input type="text" class="form-control" placeholder="影片標題" name="title" id="title" style="margin-top:10px;"></br>
            </div>
            <div class="input-group">
              <textarea type="text" class="form-control" placeholder="內文" name="videodescription" id="videodescription" style="margin-top:10px;"></textarea></br>
            </div>
           <button type="button" class="btn btn-dark" id="submitmsg" style="margin-top:20px;">新增該影片</button>
       </form>
    </div>
    <script src="js/videoupload.js" type='text/javascript'></script>
  </body>
</html>
