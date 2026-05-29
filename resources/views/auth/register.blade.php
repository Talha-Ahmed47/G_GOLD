@extends('website.master')

@section('content')
<section class="auth-section" style="padding: 120px 0 80px; min-height: 80vh; display: flex; align-items: center;">
    <div class="container">
        <div class="auth-card" style="max-width: 450px; margin: 0 auto; background: var(--bg-card); padding: 40px; border-radius: 12px; border: 1px solid var(--border-color);">
            <div class="section-header" style="text-align: center; margin-bottom: 30px;">
                <h2 class="section-title" style="font-size: 28px; margin-bottom: 10px;">Create Account</h2>
                <p style="color: var(--text-muted);">Start your gold investment journey</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                @if($errors->any())
                    <div style="color: #ff4d4d; margin-bottom: 15px; text-align: center; font-size: 14px;">
                        {{ $errors->first() }}
                    </div>
                @endif
                
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-input" value="{{ old('email') }}" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-input" required minlength="8">
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">Create Account</button>
                
                <div style="text-align: center; margin-top: 20px; color: var(--text-muted);">
                    Already have an account? <a href="/login" style="color: var(--gold-light);">Login</a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
