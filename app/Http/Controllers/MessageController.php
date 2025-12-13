<?php

namespace App\Http\Controllers;

//   ✅ Features in this controller:
//      - Returns full conversation between two users (index)
//      - Accepts text and/or image in store()
//      - Validates image uploads and limits size
//      - Stores image in storage/app/public/uploads/messages
//      - Returns JSON for SPA consumption

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//added hoping for a miracle cure
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    /**
     * Get conversation messages between authenticated user and another user.
     */
    public function index($userId)
    {
        $authId = Auth::id();

        // Get all messages between auth user and selected user
        $messages = Message::where(function($q) use ($authId, $userId) {
                $q->where('sender_id', $authId)
                  ->where('receiver_id', $userId);
            })
            ->orWhere(function($q) use ($authId, $userId) {
                $q->where('sender_id', $userId)
                  ->where('receiver_id', $authId);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    /**
     * Send a new message (text and/or image)
     */


    public function store(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'nullable|string',
            'image' => 'nullable|image|max:2048', // optional image
        ]);

        // Ensure at least one of text or image exists
        if (empty($validated['message']) && !$request->hasFile('image')) {
            return response()->json(['error' => 'Message or image required'], 422);
        }

        // Handle image upload if present
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('chat_images', 'public');
        }

        // Create the message
        $message = Message::create([
            'sender_id'   => auth()->id(),
            'receiver_id' => $validated['receiver_id'],
            'message'     => $validated['message'] ?? '',
            'image_path'  => $imagePath // JRL trailing comma removed
        ]);

        return response()->json($message, 201);
    }


public function uploadImage(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048', // Max size: 2MB
            'receiver_id' => 'required|exists:users,id',
        ]);

        // Store the image in the public disk (storage/app/public/images)
        $path = $request->file('image')->store('images', 'public');

        // Get the URL of the stored image
        $imagePath = Storage::url($path); // This returns the public URL
        
        //$imagePath = '/storage/' . $path; // Adjust if your public disk is configured differently

        // Save the image path in the database (for example, in the "posts" table)
        // Assuming you have a "Post" model where you want to store the image path
        // We'll create a new post for demonstration purposes

        // Create the message
        $message = Message::create([
            'sender_id'   => auth()->id(),
            'receiver_id' => $validated['receiver_id'],
            'message'     => null,
            'image_path'  => $imagePath // JRL trailing comma removed, above line re-enabl
        ]);

        // $post = new Post();  // If you're attaching the image to an existing post, find it first
        // $post->image_path = $imagePath; // Save the image path in the 'image_path' column
        // $post->save(); // Save the post with the image path

        // Return the image path as a response
        return response()->json([
            'success' => true,
            'imagePath' => $imagePath, // Return the public URL of the uploaded image
            'message_id' => $message->id  // Return the ID of the post (if needed)
        ]);
    }


    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'receiver_id' => 'required|exists:users,id',
    //         'message' => 'nullable|string',
    //         'image' => 'nullable|image|max:2048', // max 2MB
    //     ]);

    //     $data = [
    //         'sender_id' => Auth::id(),
    //         'receiver_id' => $request->receiver_id,
    //         'message' => $request->message,
    //     ];

    //     // Handle image upload if present
    //     if ($request->hasFile('image')) {
    //         $path = $request->file('image')->store('uploads/messages', 'public');
    //         $data['image_path'] = $path;
    //     }

    //     // NOT IN THE UPDATED VERSION
    //     $message = Message::create($data);

    //     return response()->json($message, 201);
    // }

    /**
     * (Optional) Mark message as read
     */
    public function markAsRead($id)
    {
        $message = Message::findOrFail($id);

        // Only receiver can mark as read
        if ($message->receiver_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $message->update(['read_at' => now()]);

        return response()->json($message);
    }
}
