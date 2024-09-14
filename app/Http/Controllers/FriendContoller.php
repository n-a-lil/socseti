<?
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Friend;

class FriendController extends Controller
{
    public function showFriends()
    {
        $userId = Auth::id();

        if ($userId) {
            $friends = Friend::where('user_id', $userId)->get();
            return view('friends', ['friends' => $friends]);
        } 
    }

}
