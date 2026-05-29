@extends('website.master')

@section('content')
<section class="auth-section" style="padding: 120px 0 80px; min-height: 80vh; display: flex; align-items: center;">
    <div class="container">
        <div class="auth-card" style="max-width: 400px; margin: 0 auto; background: var(--bg-card); padding: 40px; border-radius: 12px; border: 1px solid var(--border-color);">
            <div class="section-header" style="text-align: center; margin-bottom: 30px;">
                <h2 class="section-title" style="font-size: 28px; margin-bottom: 10px;">Welcome Back</h2>
                <p style="color: var(--text-muted);">Login to your Aurum Gold account</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf
              
                @if($errors->any())
                    <div style="color: #ff4d4d; margin-bottom: 15px; text-align: center; font-size: 14px;">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-input" value="{{ old('email') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-input" required>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">Login securely</button>

                <div style="text-align: center; margin-top: 20px; color: var(--text-muted);">
                    Don't have an account? <a href="/register" style="color: var(--gold-light);">Sign Up</a>
                </div>
            </form>
            <a href="{{ url('/auth/google') }}" class="btn btn-danger" style="width:100%; margin-top:10px;">
                Login with Google
            </a>
        </div>
    </div>
</section>
@endsection
@push('js')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endpush