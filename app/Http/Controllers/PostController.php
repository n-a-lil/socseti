<?php
    namespace App\Http\Controllers;
    
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Http\Request;
    use App\Models\Post;
    use App\Models\User;

    class PostController extends Controller
    {
        public function showLayout()
        {
            $userId = Auth::id(); 
    
            if ($userId) {
                $user = User::find($userId); 
                $userName = $user->name; 
                return view('layout', ['userName' => $userName]); 
            } 
    
            return view('layout');
        }     
    }

?>