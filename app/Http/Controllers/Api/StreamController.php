<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Stream;
use App\Models\StreamViewer;
use Illuminate\Http\Request;

class StreamController extends Controller
{
    public function startStream(Request $request)
    {
        
        $request->validate([
            'stream_key' => 'required|string',
            'user_id' => 'required|integer|exists:users,id',
        ]);

        // Check if the stream key is valid
        $stream = Stream::where('stream_key', $request->stream_key)
            ->where('user_id', $request->user_id)
            ->first();
        if ($stream) {
            return response(['status' => 'error', 'code' => 403,   'data' => null, 'message' => 'Stream key is already in use'], 403);
        }
        // Create a new stream
        $stream = Stream::create([
            'user_id' => $request->user_id,
            'stream_key' => $request->stream_key,
        ]);
        if ($stream) {
            $stream = Stream::where('stream_key', $request->stream_key)
            ->where('user_id', $request->user_id)->with('user')
            ->first();
            return response(['status' => 'success', 'code' => 200,  'data' => $stream, 'message' => 'Stream started successfully'], 200);
        } else {
            return response(['status' => 'error', 'code' => 500,   'data' => null, 'message' => 'Failed to start stream'], 500);
        }
    }

    public function stopStream(Request $request)
    {

        $request->validate([
            'stream_key' => 'required|string',
            'user_id' => 'required|integer|exists:users,id',
        ]);

        // Find the stream
        $stream = Stream::where('stream_key', $request->stream_key)
            ->where('user_id', $request->user_id)->with('user')
            ->first();

        if ($stream) {
            $stream->update(['status' => 0]);
            return response(['status' => 'success', 'code' => 200,  'data' => $stream, 'message' => 'Stream stopped successfully'], 200);
        } else {
            return response(['status' => 'error', 'code' => 404,   'data' => null, 'message' => 'Stream not found'], 404);
        }
    }
    public function getAllLiveStreams(Request $request)
    {

        $streams = Stream::where('status', 1)->with('user')->get();

        if ($streams->isEmpty()) {
            return response(['status' => 'error', 'code' => 404,   'data' => null, 'message' => 'No live streams found'], 404);
        } else {
            return response(['status' => 'success', 'code' => 200, 'data' => $streams, 'message' => 'Live streams retrieved successfully'], 200);
        }
    }

    public function viewStream(Request $request)
    {
        $request->validate([
            'stream_id' => 'required|string|exists:streams,id',
        ]);
        
        $stream = Stream::where(['id'=> $request->stream_id,'status'=>1])->first();
        
        if(!$stream){
            return response(['status' => 'error', 'code' => 404,   'data' => null, 'message' => 'No live streams found'], 404);
        }
        
        $user = auth()->user();
        $stream = StreamViewer::create([
            'stream_id' => $stream->id,
            'user_id' => $user->id,
        ]);
        
        if ($stream) {
            return response(['status' => 'success', 'code' => 200,  'data' => $stream, 'message' => 'Stream viewed successfully'], 200);
        } else {
            return response(['status' => 'error', 'code' => 500,   'data' => null, 'message' => 'Failed to view stream'], 500);
        }
    }
}
