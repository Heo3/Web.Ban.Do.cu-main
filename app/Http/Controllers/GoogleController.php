<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Throwable $e) {
            Log::error('Google Login Error: ' . $e->getMessage());
            return redirect()->route('login')->withErrors([
                'login' => 'Không thể kết nối với tài khoản Google hoặc bạn đã hủy đăng nhập. Vui lòng thử lại.'
            ]);
        }

        if (empty($googleUser->getEmail())) {
            return redirect()->route('login')->withErrors([
                'login' => 'Không tìm thấy địa chỉ email từ tài khoản Google của bạn.'
            ]);
        }

        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if (!$user) {
            $user = User::create([
                'email'             => $googleUser->getEmail(),
                'google_id'         => $googleUser->getId(),
                'password'          => null,
                'email_verified_at' => now(),
                'role'              => 'user',
                'status'            => 'active',
            ]);

            UserProfile::create([
                'user_id' => $user->id,
                'name'    => $googleUser->getName() ?? 'Người dùng Google',
                'avatar'  => $googleUser->getAvatar(),
            ]);
        } else {
            // Kiểm tra nếu tài khoản bị khóa
            if ($user->isBanned()) {
                return redirect()->route('login')->withErrors([
                    'login' => 'Tài khoản của bạn đã bị khóa bởi Quản trị viên.'
                ]);
            }

            $user->update([
                'google_id'         => $googleUser->getId(),
                'email_verified_at' => $user->email_verified_at ?? now(),
            ]);

            if (!$user->profile) {
                UserProfile::create([
                    'user_id' => $user->id,
                    'name'    => $googleUser->getName() ?? ($user->sdt ?? 'Người dùng Google'),
                    'avatar'  => $googleUser->getAvatar(),
                ]);
            } elseif (empty($user->profile->avatar) && $googleUser->getAvatar()) {
                $user->profile->update([
                    'avatar' => $googleUser->getAvatar(),
                ]);
            }
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->intended('/');
    }
}