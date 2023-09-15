<?php

namespace App\Http\Controllers\API;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    // Fetch all notifications for a user
    public function index(Request $request)
    {
        $user = $request->user(); // Get the authenticated user
        $notifications = Notification::where('user_id', $user->id)->get();
        return response()->json($notifications);
    }

    // Mark a notification as read
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['status' => 'read']);
        return response()->json(['message' => 'Notification marked as read']);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'user_role' => 'required|integer',
            'activity_type' => 'required|string',
            'activity_data' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => 422,
                    'errors' => $validator->messages(),
                ],
                422
            );
        } else {
            $notification = Notification::create([
                'user_id' => $request->input('user_id'),
                'user_role' => $request->input('user_role'),
                'activity_type' => $request->input('activity_type'),
                'activity_data' => $request->input('activity_data'),
                'status' => 'unread',
            ]);

            if ($notification) {
                return response()->json(
                    [
                        'status' => 200,
                        'message' => 'Notification stored successfully',
                    ],
                    200
                );
            } else {
                return response()->json(
                    [
                        'status' => 500,
                        'message' => 'Failed to store notification',
                    ],
                    500
                );
            }
        }
    }
}

