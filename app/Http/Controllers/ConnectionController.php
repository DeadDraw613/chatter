<?php

namespace App\Http\Controllers;

use App\Models\Connection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConnectionController extends Controller
{
    /**
     * Send a connection request to another user.
     * Endpoint: POST /api/connections/request
     */
    public function requestConnection(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $authUser = Auth::user();
        $targetUser = User::where('email', $request->email)->first();

        // Prevent self-connections
        if ($authUser->id === $targetUser->id) {
            return response()->json(['message' => 'You cannot connect with yourself.'], 400);
        }

        // Normalize order to avoid duplicates
        $userA = min($authUser->id, $targetUser->id);
        $userB = max($authUser->id, $targetUser->id);

        // Check if connection exists
        $connection = Connection::firstOrNew([
            'user_a_id' => $userA,
            'user_b_id' => $userB,
        ]);

        if ($connection->exists && $connection->status === 'active') {
            return response()->json(['message' => 'You are already connected.'], 409);
        }

        // Set status and requester
        $connection->status = 'requested';
        $connection->requester_id = $authUser->id;
        $connection->removed_by_a = 0;
        $connection->removed_by_b = 0;
        $connection->save();

        return response()->json(['message' => 'Connection request sent.'], 201);
    }

    /**
     * Respond to a connection request (accept or refuse)
     * Endpoint: POST /api/connections/{id}/respond
     * Body: { "action": "accept" | "refuse" }
     */
    public function respondToRequest(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:accept,refuse',
        ]);

        $connection = Connection::findOrFail($id);
        $authUser = Auth::user();

        // Only non-requester can respond
        if ($authUser->id === $connection->requester_id) {
            return response()->json(['message' => 'You cannot respond to your own request.'], 403);
        }

        if ($connection->status !== 'requested') {
            return response()->json(['message' => 'This connection request is no longer pending.'], 400);
        }

        if ($request->action === 'accept') {
            $connection->update(['status' => 'active']);
            $msg = 'Connection accepted.';
        } else {
            $connection->update(['status' => 'refused']);
            $msg = 'Connection refused.';
        }

        return response()->json(['message' => $msg], 200);
    }

    /**
     * Deactivate or remove a connection (API)
     * Endpoint: DELETE /api/connections/{id}
     */
    public function removeConnection($id)
    {
        $authUser = Auth::user();
        $connection = Connection::findOrFail($id);

        if (!$connection->involves($authUser->id)) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $connection->update(['status' => 'deactivated']);

        return response()->json(['message' => 'Connection deactivated.'], 200);
    }


    public function removeFromSidebar($id)
    {

        // dd("HIT!", $id);
        \Log::info("REMOVE-SIDEBAR HIT", ['id' => $id, 'user' => auth()->id()]);

        $authId = auth()->id();
        $connection = Connection::findOrFail($id);

        if (! $connection->involves($authId)) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        if ($authId === $connection->user_a_id) {
            $connection->update(['removed_by_a' => true]);
        } else {
            $connection->update(['removed_by_b' => true]);
        }

        return response()->json(['message' => 'Connection removed from sidebar.']);
    }

    /**
     * List all current connections for the logged-in user (API)
     */
    public function index()
    {
        $authId = Auth::id();

        return Connection::with(['userA', 'userB'])
            ->where('user_a_id', $authId)
            ->orWhere('user_b_id', $authId)
            ->get();
    }

    /**
     * List connections for web (sidebar)
     */
    public function indexWeb()
    {
        $authUser = auth()->user();

        // $connections = Connection::where(function($q) use ($authUser) {
        //     $q->where('user_a_id', $authUser->id)
        //     ->orWhere('user_b_id', $authUser->id);
        // })
        // ->with(['userA', 'userB'])
        // ->get()
        // ->filter(function($c) use ($authUser) {
        //     if ($authUser->id === $c->user_a_id) return ! $c->removed_by_a;
        //     return ! $c->removed_by_b;
        // })
        // ->values();

        $connections = Connection::where(function($q) use ($authUser) {
            $q->where('user_a_id', $authUser->id)
              ->orWhere('user_b_id', $authUser->id);
        })->with(['userA', 'userB'])->get();

        return response()->json($connections);
    }

    /**
     * Remove connection from web (sidebar)
     */
    public function removeConnectionWeb($id)
    {
        $authUser = auth()->user();
        $connection = Connection::findOrFail($id);

        if (! $connection->involves($authUser->id)) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $connection->update(['status' => 'deactivated']);

        return response()->json(['message' => 'Connection removed.']);
    }
}
