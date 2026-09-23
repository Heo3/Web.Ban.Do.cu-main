<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class VerificationController extends Controller
{
    /**
     * Hiển thị trang thông báo "Vui lòng xác minh email".
     */
    public function notice(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('home')->with('success', 'Email của bạn đã được xác minh.');
        }

        return view('auth.verify-email');
    }

    /**
     * Xử lý khi user click vào link trong email.
     */
    public function verify(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('home')->with('success', 'Email đã được xác minh trước đó.');
        }

        $request->fulfill(); // Đánh dấu email_verified_at = now()

        return redirect()->route('home')->with('success', 'Xác minh Gmail thành công! Tài khoản của bạn đã được kích hoạt.');
    }

    /**
     * Gửi lại mail xác minh.
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('home')->with('success', 'Email đã được xác minh.');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'Đã gửi lại link xác minh! Vui lòng kiểm tra hộp thư Gmail.');
    }
}

