<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Resources\Message as MessageResource;

use App\User;
use App\Channel;
use App\Message;
use App\File;

use App\Events\MessageSended;
use App\Events\MessageDeleted;
use App\Events\MessageEdited;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{

    public function getMessages(Request $request, $channelId){
        $howManyMessages = 50;
        if($request->input('limit'))
            $howManyMessages = $request->input('limit');
        $user = Auth::id();

        if(Auth::user()->channels()->where([ ['channel_id', '=', $channelId], ['user_id', '=', $user], ])->count() < 1 ){
            return response("unauthorized", 401);
        }

        $messages = Message::where([
            ['channel_id', '=', $channelId],
            // ['user_id', '=', $user]
            ])
            ->orderBy('id', 'desc')
            ->take($howManyMessages)
            ->get();

        // dd(MessageResource::collection($messages));

        return MessageResource::collection($messages);
    }

    public function sendMessage(Request $request, $channelId){
        $message = $request->input('content');
        // $message = $request->content;
        // $message = request('content');

        $this->validate($request, [
            "content" => "required|min:1|max:5000",
        ]);

        $user = Auth::id();

        // dd('`'. $message. '`, '. Auth::user()->channels()->where([ ['channel_id', '=', $channelId], ['user_id', '=', $user], ])->count(). ', '. Auth::user()->channels()->where([ ['channel_id', '=', $channelId], ['user_id', '=', $user], ])->get(). ', '. $user);

        if(empty(trim($message)) || Auth::user()->channels()->where([ ['channel_id', '=', $channelId], ['user_id', '=', $user], ])->count() < 1 ){
            return response("unauthorized", 401);
        }

        // Message::create('contnent', 'user_id', 'channel_id');
        $msg = Message::create([
            'content' => $message,
            'user_id' => $user,
            'channel_id' => $channelId,
        ]);
        $msg->save();

        event(new MessageSended($msg));

        return response($msg, 201);

    }

    public function sendMessageWithFile(Request $request, $channelId){

        // dump($request->content);
        // dd($request->file);

        $user = Auth::id();

        if(Auth::user()->channels()->where([ ['channel_id', '=', $channelId], ['user_id', '=', $user], ])->count() < 1 ){
            return response("unauthorized", 401);
        }

        $content = $request->content;
        $file = $request->file('file');
        $fileType = 'file';
        $fileWidth = null;
        $fileHeight = null;
        $fileUrl = '';
        $fileName = '';

        $request->validate([
            "content" => "max:5000",
            'file' => 'required|file|max:8192',
        ]);

        // dd($file->getMimeType());

        if(strpos($file->getMimeType(), 'image') !== false){
            $fileType = 'image';

            $img = Image::make($file);
            $img->resize(400, 300, function ($constraint) {
                $constraint->aspectRatio();
            });

            $fileHeight = $img->height();
            $fileWidth = $img->width();
        }else if($file->getMimeType() == 'video/mp4'){
            $fileType = 'video';
        }else if($file->getMimeType() == 'audio/mpeg' || $file->getMimeType() == 'audio/ogg'){
            $fileType = 'audio';
        }

        // $file

        $msg = Message::create([
            'content' => $content,
            'user_id' => $user,
            'channel_id' => $channelId,
        ]);
        $msg->save();

        $fileName = $msg->id . '_' . $file->getClientOriginalName();
        $file->storeAs('public/uploads', $fileName);
        $fileUrl = 'storage/uploads/' . $fileName;

        $msgFile = File::create([
            'width' => $fileWidth,
            'height' => $fileHeight,
            'url' => $fileUrl,
            'type' => $fileType,
            'message_id' => $msg->id,
        ]);
        $msgFile->save();

        event(new MessageSended($msg));

        return response([$msg, $msgFile], 201);
    }

    public function getMessage(Request $request, $channelId, $messageId){
        $user = Auth::id();

        if(Auth::user()->channels()->where([ ['channel_id', '=', $channelId], ['user_id', '=', $user], ])->count() < 1 ){
            return response("unauthorized", 401);
        }

        $message = Message::where([
            ['channel_id', '=', $channelId],
            ['id', '=', $messageId],
            // ['user_id', '=', $user]
            ])
            ->get();

        return MessageResource::collection($message);
    }

    public function editMessage(Request $request, $channelId, $messageId){
        $user = Auth::id();

        $messageIsCorrectAndFromUser = (bool) Message::where([['id', '=', $messageId], ['channel_id', '=',  $channelId], ['user_id', '=', $user]])->count();
        $canEditOthers = Auth::user()->hasPermissionTo(['edit-others-messages']);

        if($messageIsCorrectAndFromUser || $canEditOthers){
            $message = Message::where([
                ['channel_id', '=', $channelId],
                ['id', '=', $messageId],
                // ['user_id', '=', $user]
                ])
                ->get();
    
            $message = Message::findOrFail($messageId);
    
            // $this->validate($request, [
            //     "content" => "min:1|max:32",
            // ]);
    
            $message->update($request->all());
    
            event(new MessageEdited($message));
    
            return response($message, 200);
        }
        return response("unauthorized", 401);        
    }

    public function deleteMessage(Request $request, $channelId, $messageId){
        $user = Auth::id();

        $messageIsCorrectAndFromUser = (bool) Message::where([['id', '=', $messageId], ['channel_id', '=',  $channelId], ['user_id', '=', $user]])->count();
        $canEditOthers = Auth::user()->hasPermissionTo(['delete-others-messages']);

        if($messageIsCorrectAndFromUser || $canEditOthers){
            $message = Message::where([
                ['channel_id', '=', $channelId],
                ['id', '=', $messageId],
                ])
                ->get();

            $message = Message::findOrFail($messageId);

            $message->delete();

            event(new MessageDeleted($message));

            return response($message, 200);
        }
        return response("unauthorized", 401); 
    }

}
