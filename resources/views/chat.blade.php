<!DOCTYPE html>
<html>
<head>
  <title>Pusher Test</title>
  <script src="https://js.pusher.com/5.0/pusher.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script><!--必填-->
  <script>

    // Enable pusher logging - don't include this in production--啟動錯誤log紀錄
    Pusher.logToConsole = true;

    var pusher = new Pusher('2136ac7bcdd5797f3527', {//設定密碼位置等資訊
      cluster: 'ap3',
      forceTLS: true
    });

    var channel = pusher.subscribe('testChannel');//設定聊天室是哪個
    channel.bind('form-submitted', function(data) {//收到後執行那些動作
      alert(JSON.stringify(data));
    });
  </script>
</head>
<body>
  <h1>Pusher Test</h1>
  <p>
    Try publishing an event to channel <code>my-channel</code>
    with event name <code>my-event</code>.
  </p>
</body>
</html>
