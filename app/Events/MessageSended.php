<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use App\User;
use App\Message;
use App\Channel as ChannelModel;
use App\File;

use App\Http\Resources\Message as MessageResource;

class MessageSended implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $user;
    public $channel;
    public $files;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Message $msg)
    {
        // dd($msg);
        // $this->message = MessageResource::collection($msg);
        $this->message = $msg;
        $this->user = User::find($msg->user_id)->getUserData();
        $this->channel = ChannelModel::find($msg->channel_id);
        $this->files = File::where('message_id', $msg->id)->get();
        // dd(User::find($msg->user_id)->id);
        // dd($msg->user_id);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('client');
    }
}
