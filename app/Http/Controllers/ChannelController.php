<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Resources\Channel as ChannelResource;

use App\User;
use App\Channel;
use App\Message;

// use App\Events\MessageSended;
use App\Events\ChannelAdded;
use App\Events\ChannelEdited;
use App\Events\ChannelDeleted;

class ChannelController extends Controller
{
    public function __construct()
    {
        //
    }

    public function getChannel(Request $request, $channelId){
        $channel = Channel::findOrFail($channelId);

        // if(Auth::user()->channels()->where([ ['id', '=', $channelId], ])->count() < 1 ){
        //     return response("unauthorized", 401);
        // }

        return new ChannelResource($channel);
    }

    public function getVisibleChannels(Request $request){
        return ChannelResource::collection(Auth::user()->channels()->get());
    }

    public function getAllChannels(Request $request){
        return ChannelResource::collection(Channel::withTrashed()->get());
    }

    public function createChannel(Request $request){
        $channel = new Channel;

        $this->validate($request, [
            "name" => "required|min:1|max:32",
        ]);

        $name = $request->name;
        $maxPosition = Channel::max('position');
        $channel = Channel::create(['name' => $name, 'position' => $maxPosition + 1]);

        // Auth::user()->channels()->attach($channel);

        // dd($request->users);

        if($request->isAllUserSelected){
            $channel->users()->sync(User::all());
        }else{
            $channel->users()->sync($request->users);
        }

        event(new ChannelAdded($channel));

        return response($channel , 201);
    }

    public function createDmChannel(Request $request){
        $user = Auth::user();
        $with = User::findOrFail($request->with);
        $channelName = '';
        if($user->id == $with->id){
            return response('Bad Request', 400);
        }else if($user->id < $with->id){
            $channelName = $user->id . '-' . $with->id;
        }else if($user->id > $with->id){
            $channelName = $with->id . '-' . $user->id;
        }else{
            // $channelName = '-';
            return response('Bad Request', 400);
        }
        if(Channel::where('name', '=', $channelName)->exists()){ 
            return response('Not Modified', 304);
        }
        $maxPosition = Channel::max('position');
        $channel = Channel::create(['name' => $channelName, 'type' => 'dm', 'position' => $maxPosition + 1]);
        
        $channel->users()->sync([$user->id, $with->id]);

        event(new ChannelAdded($channel));

        return response($channel, 201);
    }

    public function editChannel(Request $request, $channelId){
        $channel = Channel::findOrFail($channelId);

        $this->validate($request, [
            "name" => "min:1|max:32",
        ]);
        
        // if(Auth::user()->channels()->where([ ['id', '=', $channelId], ])->count() < 1 ){
        //     return response("unauthorized", 401);
        // }

        $channel->update($request->all());
        
        if($request->users == []){
            $channel->users()->sync([]);
        }else if($request->isAllUserSelected || $request->users){
            if($request->isAllUserSelected){
                $channel->users()->sync(User::all());
            }else{
                $channel->users()->sync($request->users);
            }
        }

        event(new ChannelEdited($channel));

        return response($channel , 200);
    }

    public function deleteChannel(Request $request, $channelId){
        $channel = Channel::findOrFail($channelId);

        // if(Auth::user()->channels()->where([ ['id', '=', $channelId], ])->count() < 1 ){
        //     return response("unauthorized", 401);
        // }

        $channel->delete();

        event(new ChannelDeleted($channel));

        return response($channel , 200);
    }

    public function undeleteChannel(Request $request, $channelId){
        $channel = Channel::onlyTrashed()->findOrFail($channelId)->restore();
        $channel = Channel::findOrFail($channelId);

        event(new ChannelEdited($channel));

        return $channel;
    }

}
