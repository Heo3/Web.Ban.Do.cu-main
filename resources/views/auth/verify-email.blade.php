@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5 text-center">
                    <div class="mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle" style="width: 80px; height: 80px;">
                            <i class="fas fa-envelope-open-text fa-2x"></i>
                        </div>
                    </div>

                    <h4 class="fw-bold mb-3">Xác minh địa chỉ Gmail</h4>

                    @if(session('status'))
                        <div class="alert alert-success alert-dismissible fade show text-start" role="alert">
                            <i class="fas fa-check-circle me-2"></i> {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <p class="text-muted fs-6 mb-4">
                        Chúng tôi đã gửi một liên kết xác minh tới địa chỉ Gmail:
                        <br>
                        <strong class="text-dark">{{ auth()->user()->email }}</strong>
                        <br>
                        Vui lòng kiểm tra hộp thư (bao gồm cả thư mục <em>Spam / Thư rác</em>) và bấm vào nút xác minh để hoàn tất kích hoạt tài khoản.
                    </p>

                    <form method="POST" action="{{ route('verification.send') }}" class="mb-3">
                        @csrf
                        <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold rounded-3">
                            <i class="fas fa-paper-plane me-2"></i> Gửi lại email xác minh
                        </button>
                    </form>

                    <hr class="my-4 text-muted opacity-25">

                    <div class="d-flex align-items-center justify-content-center gap-2">
                        <span class="small text-muted">Nhập sai địa chỉ Gmail?</span>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link btn-sm text-danger text-decoration-none fw-semibold p-0">
                                Đăng xuất & đăng ký lại
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
