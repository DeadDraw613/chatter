<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Message;

class ImageController extends Controller
{
    public function upload(Request $request)
    {
        // return response()->json(['hit' => true]);
        // \Log::info('tits: Uplbhbhbvoad started');
        
        // Return JSON validation errors instead of redirect HTML
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|max:4096',
            'receiver_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();
        // \Log::info('tits: 1st-receiver_id: ' . $validator['receiver_id']);
        // \Log::info('DEBUG: Validated receiver_id: ' . $validated['receiver_id']);

        // \Log::info('tits: Validated data: ' . json_encode($validated));

        // ✅ Get uploaded file
        $file = $request->file('image');

        // \Log::info('tits: File retrieved: ' . ($file ? $file->getClientOriginalName() : 'none'));

        // ✅ Create unique filename
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

        // ✅ Save to /public/uploads/
        $file->move(public_path('uploads'), $filename);

        // ✅ URL for frontend
        $imagePath = '/uploads/' . $filename;

        // \Log::info('tits: imagepath to write to DB: ' . $imagePath);
        // \Log::info('tits: sender_id: ' . auth()->id());
        // \Log::info('tits: receiver_id: ' . $validated['receiver_id']);


        // ✅ Save message to DB
        $message = Message::create([
            'sender_id'   => auth()->id(),
            'receiver_id' => $validated['receiver_id'],
            'message'     => null,
            'image_path'  => $imagePath,
        ]);

        return response()->json([
            'success'     => true,
            'image_path'  => $imagePath,
            'message_id'  => $message->id
        ], 200);
    }
}

?>