<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Post;
use App\Models\Media;
use App\Models\Banner;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index', [
            'pagesCount'          => Page::count(),
            'postsCount'          => Post::count(),
            'postsPublishedCount' => Post::where('status', 'published')->count(),
            'postsDraftCount'     => Post::where('status', 'draft')->count(),
            'mediaCount'          => Media::count(),
            'bannersCount'        => Banner::count(),
            'usersCount'          => User::count(),
            'recentPosts'         => Post::with('author')->latest()->take(5)->get(),
            'recentPages'         => Page::with('author')->latest()->take(5)->get(),
        ]);
    }
    public function notifications()
    {
        $user = auth()->user();
        $notifications = $user->notifications()->take(10)->get()->map(function ($notification) {
            return [
                'id' => $notification->id,
                'data' => $notification->data,
                'read_at' => $notification->read_at,
                'created_at' => $notification->created_at->diffForHumans(),
            ];
        });
        $unreadCount = $user->unreadNotifications()->count();

        return response()->json([
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }

    public function markNotificationsAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }
}
