<?php

namespace App\Http\Controllers;

use App\Events\BroadcastEvent;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Notifications\PostLikeNotification;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $posts = Post::with('user')->get();

        return view('home', ['posts' => $posts]);
    }

    public function postLike(Request $request)
    {
        $user = auth()->user();

        $post = Post::whereId($request->post_id)->with('user')->first();
        // like code -----skip
        $author = $post->user;

//        event(new BroadcastEvent('Demo notifications'));

        $author->notify(new PostLikeNotification($user, $post));

        return response()->json(['Success']);
    }
}
