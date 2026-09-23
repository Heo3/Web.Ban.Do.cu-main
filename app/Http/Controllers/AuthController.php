<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserProfile;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required',
            'password' => 'required',
        ]);

        $loginField = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'sdt';

        if (Auth::attempt([
            $loginField => $credentials['login'],
            'password' => $credentials['password'],
        ])) {
            if (Auth::user()->isBanned()) {
                Auth::logout();
                return back()->withErrors([
                    'login' => 'Tài khoản của bạn đã bị khóa bởi Quản trị viên.',
                ]);
            }

            $request->session()->regenerate();

            return redirect('/');
        }

        return back()->withErrors([
            'login' => 'Gmail, số điện thoại hoặc mật khẩu không chính xác.',
        ]);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'sdt'      => 'nullable|string|max:20|unique:users,sdt',
            'email'    => 'nullable|email|max:255|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ], [
            'name.required'      => 'Vui lòng nhập tên.',
            'sdt.unique'         => 'Số điện thoại này đã được đăng ký.',
            'email.unique'       => 'Email này đã được đăng ký.',
            'email.email'        => 'Email không đúng định dạng.',
            'password.required'  => 'Vui lòng nhập mật khẩu.',
            'password.min'       => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        if (empty($data['sdt']) && empty($data['email'])) {
            return back()->withErrors([
                'login' => 'Vui lòng nhập số điện thoại hoặc Gmail.',
            ])->withInput();
        }

        $user = User::create([
            'sdt'      => $data['sdt'] ?? null,
            'email'    => $data['email'] ?? null,
            'password' => $data['password'],
            'role'     => 'user',
            'status'   => 'active',
        ]);

        UserProfile::create([
            'user_id' => $user->id,
            'name'    => $data['name'],
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        // Nếu đăng ký có Email -> gửi mail xác minh và đến trang thông báo
        if (!empty($user->email)) {
            $user->sendEmailVerificationNotification();
            return redirect()->route('verification.notice')
                ->with('status', 'Đăng ký thành công! Vui lòng kiểm tra Gmail để xác minh tài khoản.');
        }

        return redirect('/');
    }


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'sdt' => 'nullable|string|max:20|unique:users,sdt,' . $user->id,
            'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'dia_chi' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Không được bỏ trống cả SĐT và Gmail
        if (empty($data['sdt']) && empty($data['email'])) {
            return back()->withErrors([
                'profile' => 'Vui lòng nhập số điện thoại hoặc Gmail.'
            ])->withInput();
        }

        // Cập nhật thông tin bảng users
        $user->update([
            'sdt' => $data['sdt'] ?? null,
            'email' => $data['email'] ?? null,
        ]);

        // Dữ liệu profile
        $profileData = [
            'name' => $data['name'],
            'dia_chi' => $data['dia_chi'] ?? null,
        ];

        // Nếu có chọn avatar
        if ($request->hasFile('avatar')) {

            $avatarPath = $request->file('avatar')->store('avatars', 'public');

            $profileData['avatar'] = $avatarPath;

        }

        // Cập nhật user_profiles
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        return redirect('/')->with(
            'success',
            'Cập nhật thông tin thành công!'
        );
    }

}