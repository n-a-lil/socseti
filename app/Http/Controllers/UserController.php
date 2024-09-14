<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Notification;
use App\Models\Friend;
use App\Models\Image;
use App\Models\Message;
use App\Models\User_music;
use App\Models\Music;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\PostLike;

class UserController extends Controller
{
    public function showMyAccount()
    {
        $userId = Auth::id();

        if ($userId) {
            $user = User::find($userId);
            return view('my_account', ['user' => $user]);
        } else {
            return redirect('/login')->with('error', 'Unauthorized access!');
        }
    }

    public function edit(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect('/login')->with('error', 'User not found!');
        }

        $user->name = $request->input('name');
        $user->surname = $request->input('surname');
        $user->email = $request->input('email');
        $user->age = $request->input('age');
        $user->save();

        return redirect('/myAccount');
    }

    public function showFriends()
    {
        $userId = Auth::id();

        if ($userId) {
            $user = User::find($userId);
            $friends = $user->friends;

            $users = User::where('id', '!=', $userId)->get();

            foreach ($users as $user) {
                $user->isFriend = $this->isFriend($user, $friends);
                $user->friendRequestSent = $this->friendRequestSent($user->id, $userId);
            }

            return view('friends', ['users' => $users]);
        } else {
            return redirect('/login');
        }
    }

    private function isFriend($user, $friends)
    {
        foreach ($friends as $friend) {
            if ($friend->id === $user->id) {
                return true;
            }
        }
        return false;
    }

    private function friendRequestSent($userId, $senderId)
    {
        return Notification::where('user_id', $userId)
                        ->where('sender_id', $senderId)
                        ->exists();
    }

    public function showMyFriends()
    {
        $userId = Auth::id();

        if ($userId) {
            $user = User::find($userId);
            $friends = $user->friends;
            return view('my_friends', ['userFriends' => $friends]);
        } else {
            return redirect('/login')->with('error', 'Unauthorized access!');
        }
    }

    public function searchFriends(Request $request)
    {
        $search = $request->input('search');

        $users = User::where('name', 'LIKE', "%$search%")
                    ->orWhere('surname', 'LIKE', "%$search%")
                    ->get();

        $userId = Auth::id();
        $currentUser = User::find($userId);
        $friends = $currentUser->friends;

        foreach ($users as $user) {
            $user->isFriend = $this->isFriend($user, $friends);
            $user->friendRequestSent = $this->friendRequestSent($user->id, $userId);
        }

        return view('search_friends', ['users' => $users, 'search' => $search]);
    }

    public function searchMyFriends(Request $request)
    {
        $userId = Auth::id();
        $search = $request->input('search');

        if ($userId) {
            $user = User::find($userId);
            $friends = $user->friends()->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%$search%")
                    ->orWhere('surname', 'LIKE', "%$search%");
            })->get();

            return view('search_my_friends', ['userFriends' => $friends, 'search' => $search]);
        }
    }

    public function showNotifications()
    {
        $userId = Auth::id();

        if ($userId) {
            $notifications = Notification::with(['receiver', 'sender'])->where('user_id', $userId)->get();
            return view('notifications', ['notifications' => $notifications]);
        } else {
            return redirect('/login')->with('error', 'Unauthorized access!');
        }
    }

    public function addFriend($senderId)
    {
        $userId = Auth::id();

        Notification::where('sender_id', $senderId)->where('user_id', $userId)->delete();

        Friend::create([
            'user_id' => $userId,
            'friend_id' => $senderId,
        ]);

        Friend::create([
            'user_id' => $senderId,
            'friend_id' => $userId,
        ]);

        return redirect('/myFriends')->with('message', 'Friend request accepted!');
    }

    public function rejectFriend($notificationId)
    {
        Notification::find($notificationId)->delete();
    }

    public function addToFriend($userId)
    {
        $senderId = Auth::id();

        Notification::create([
            'user_id' => $userId,
            'sender_id' => $senderId,
        ]);

        return redirect('/myFriends')->with('message', 'Friend request sent!');
    }

    public function showUserProfile($id)
    {
        $user = User::find($id);
        $userId = Auth::id();

        $mainImage = Image::where('user_id', $user->id)
                        ->where('main', 1)
                        ->first();

        $isFriend = false;
        $friendRequestSent = false;

        if ($userId) {
            $friendship = Friend::where('user_id', $userId)
                                ->where('friend_id', $id)
                                ->first();

            if ($friendship) {
                $isFriend = true;
            } else {
                $friendRequestSent = Notification::where('user_id', $id)
                                                ->where('sender_id', $userId)
                                                ->exists();
            }
        }

        return view('user_profile', [
            'user' => $user,
            'mainImage' => $mainImage, 
            'isFriend' => $isFriend,
            'friendRequestSent' => $friendRequestSent
        ]);
    }

    public function cancelFriendRequest($userId)
    {
        $senderId = Auth::id();

        Notification::where('user_id', $userId)
                    ->where('sender_id', $senderId)
                    ->delete();

        return redirect('/user/'.$userId)->with('message', 'Friend request cancelled!');
    }

    public function removeFromFriends($friendId)
    {
        $userId = Auth::id();

        Friend::where('user_id', $userId)
            ->where('friend_id', $friendId)
            ->delete();

        Friend::where('user_id', $friendId)
            ->where('friend_id', $userId)
            ->delete();

        return redirect('/myFriends')->with('message', 'Friend removed successfully!');
    }

    public function showMessages()
    {
        $userId = Auth::id();

        if ($userId) {
            $messages = Message::with(['sender', 'receiver'])
                ->where('receiver_id', $userId)
                ->orWhere('sender_id', $userId)
                ->get();

            return view('messages', ['messages' => $messages]);
        } else {
            return redirect('/login')->with('error', 'Unauthorized access!');
        }
    }

    public function showDialog(Request $request, $userId)
    {
        $user = User::find($userId); 

        $messages = Message::where(function ($query) use ($userId) {
            $query->where('sender_id', Auth::id())
                ->where('receiver_id', $userId);
        })->orWhere(function ($query) use ($userId) {
            $query->where('sender_id', $userId)
                ->where('receiver_id', Auth::id());
        })->get();

        return view('messageDialog', compact('messages', 'user'));
    }   

    public function sendMessage(Request $request, $userId)
    {
        $senderId = Auth::id();
        $text = $request->input('text');

        $message = new Message();
        $message->receiver_id = $userId;
        $message->sender_id = $senderId;
        $message->text = $text;
        $message->save();

        return redirect('/messages');
    }

    public function showMusic()
    {
        $userId = Auth::id();

        if ($userId) {
            $userMusic = User_music::where('user_id', $userId)->get();

            return view('music', ['userMusic' => $userMusic]);
        }
    }     

    public function showAllMusic()
    {
        $Musics = Music::all();

        return view('allmusic',['Musics' => $Musics]);
    }     

    public function searchAllMusic(Request $request)
    {
        $search = $request->input('search');

        $musics = Music::where('music_title', 'LIKE', "%$search%")
                    ->orWhere('music_artist', 'LIKE', "%$search%")
                    ->get();

        return view('search_music', ['musics' => $musics, 'search' => $search]);
    }

    public function searchMyMusic(Request $request)
    {
        $userId = Auth::id();
        $search = $request->input('search');

        if ($userId) {
            $userMusics = User_music::where('user_id', $userId)
                            ->where(function ($query) use ($search) {
                                $query->where('music_title', 'LIKE', "%$search%")
                                    ->orWhere('music_artist', 'LIKE', "%$search%");
                            })
                            ->get();

            return view('search_my_music', ['userMusics' => $userMusics, 'search' => $search]);
        }
    }

    public function showPosts()
    {
        $posts = Post::all();
        return view('allposts', ['posts' => $posts]);
    }

    public function addPosts()
    {
        return view('addposts');
    }

    public function addPost(Request $request)
    {
        $userId = Auth::id();

        if ($userId) {
            $post = new Post();
            $post->title = $request->input('title');
            $post->text = $request->input('text');
            $post->image = $request->input('image');

            $post->user_id = $userId;

            $post->save();

            return view('addposts');
        }
    }

    public function showPost($id)
    {
        $post = Post::find($id);
        $comments = PostComment::where('post_id', $id)->with('user')->get();

        return view('posts', ['post' => $post, 'comments' => $comments]);
    }

    public function likePost(Request $request)
    {
        $postId = $request->input('post_id');
        $userId = Auth::id();

        if ($userId) {
            $existingLike = PostLike::where('post_id', $postId)
                                    ->where('user_id', $userId)
                                    ->first();

            if ($existingLike) {
                $existingLike->delete();
            } else {
                $like = new PostLike();
                $like->post_id = $postId;
                $like->user_id = $userId;
                $like->save();
            }

            $likeCount = Post::find($postId)->likes()->count();

            return $likeCount;
        }
    }

    public function addComment(Request $request, $postId)
    {
        $userId = Auth::id();
        $commentText = $request->input('comment_text');

        if ($userId) {
            $comment = new PostComment();
            $comment->post_id = $postId;
            $comment->user_id = $userId;
            $comment->comment_text = $commentText;
            $comment->save();

            return $comment->user->name . ': ' . $comment->comment_text;
        } else {
            return 'Unauthorized access!';
        }
    }
    
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}