@extends('frontend.layouts.app')

@section('title', 'حسابي - توريد ميد')

@section('content')
<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-user-circle fa-4x text-success"></i>
                    </div>
                    <h4 class="fw-bold">{{ $customer->display_name ?? $customer->name }}</h4>
                    <p class="text-muted mb-1">
                        <i class="fas fa-envelope-open me-1 text-primary"></i>
                        {{ $customer->email }}
                    </p>
                    <p class="text-muted mb-3">
                        <i class="fas fa-phone me-1 text-primary"></i>
                        {{ $customer->phone ?? 'غير متوفر' }}
                    </p>
                    <span class="badge bg-success-subtle text-success px-3 py-2">
                        <i class="fas fa-check-circle me-1"></i> حساب عميل
                    </span>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-user-edit me-2 text-success"></i>
                        تحديث البيانات الشخصية
                    </h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('frontend.customer.profile.update') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">الاسم الكامل</label>
                            <input type="text"
                                   name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $customer->name) }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">البريد الإلكتروني</label>
                            <input type="email"
                                   name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $customer->email) }}"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">رقم الهاتف</label>
                            <input type="tel"
                                   name="phone"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone', $customer->phone) }}"
                                   required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-success px-4">
                            <i class="fas fa-save me-2"></i> حفظ التغييرات
                        </button>
                        <a href="{{ route('frontend.customer.orders') }}" class="btn btn-outline-secondary px-4 ms-2">
                            <i class="fas fa-box-open me-2"></i> طلباتي
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

