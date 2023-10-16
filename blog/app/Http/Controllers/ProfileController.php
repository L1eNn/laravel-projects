<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use function PHPUnit\Framework\fileExists;
use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

class ProfileController extends Controller
{
    public function index() {
        $user = Auth::user();
        if (isset($user->profile)) {
            $avatarCreatedDate = $user->profile->created_at;
            $avatarCreatedDate = str_replace(':', '-', $avatarCreatedDate);
            $avatarSrc = '';
            try {
                if (!file_exists(public_path('avatars/'.$avatarCreatedDate.' '.$user->profile->avatar))) {
                    throw new \Exception('File not found');
                }
                $avatarSrc = 'avatars/'.$avatarCreatedDate.' '.$user->profile->avatar;
            } catch (\Exception $exception) {
                $avatarSrc = 'icons/no-avatar.png';
            }
            return ['profileInfo' => $user->profile, 'avatarSrc' => $avatarSrc];
        }
        return "No profile";
    }

    public function store(Request $request) {
        $data = $request->validate([
            'user_id' => 'required',
            'firstName' => 'required|string',
            'secondName' => 'required|string',
            'birthday' => 'required|date',
            'country' => 'nullable|string',
            'city' => 'nullable|string',
        ]);

        $avatar = '';

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $file->move(public_path('avatars'), date('Y-m-d H-i-s').' '.$file->getClientOriginalName());
            $avatar = $file->getClientOriginalName();
        }



        Profile::create([
            'user_id' => $data['user_id'],
            'avatar' => $avatar,
            'first_name' => $data['firstName'],
            'second_name' => $data['secondName'],
            'birthday' => $data['birthday'],
            'country' => $data['country'],
            'city' => $data['city'],
        ]);

        return "Profile successfully created";
    }

    public function getAuthUserProfile() {
        $user = Auth::user();
        return isset($user->profile);
    }
}
