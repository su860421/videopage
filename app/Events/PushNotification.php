<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Events\PushNotification;

class PushNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $id;//websocket事件
    public $email;
    public $msg;
    public $title;
    public $updated_at;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message)
    {
        //
        $this->id=$message['id'];
        $this->email=$message['email'];
        $this->msg=$message['msg'];
        $this->title=$message['title'];
        $this->updated_at=$message['updated_at'];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()//聊天室名稱
    {
        return new Channel('testChannel');
    }
    public function broadcastAs(){
      return 'form-submitted';
    }
}
