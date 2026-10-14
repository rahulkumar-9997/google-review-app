@extends('backend.layouts.master')
@section('title','Add Google Review Shop')
@push('styles')
@endpush
@section('main-content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xxl-12 col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Add Shop</h4>
                        <div class="flex-shrink-0">
                            <div class="form-check form-switch form-switch-right form-switch-md">
                                <a href="{{ route('manage-google-reviews.index') }}" class="btn btn-info custom-toggle active">
                                    <i class="ri-arrow-left-line align-bottom me-1"></i>
                                    Back to List
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('manage-google-reviews.store') }}" method="POST" enctype="multipart/form-data" id="shopCreateForm">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Shop Name *</label>
                                        <input
                                            type="text"
                                            class="form-control @error('name') is-invalid @enderror"
                                            id="name"
                                            name="name"
                                            placeholder="Enter shop name"
                                            value="{{ old('name') }}">
                                        @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="address" class="form-label">Address</label>
                                        <input
                                            type="text"
                                            class="form-control @error('address') is-invalid @enderror"
                                            id="address"
                                            name="address"
                                            placeholder="Enter shop address"
                                            value="{{ old('address') }}">
                                        @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="google_review_url" class="form-label">Google Review URL</label>
                                        <input
                                            type="url"
                                            class="form-control @error('google_review_url') is-invalid @enderror"
                                            id="google_review_url"
                                            name="google_review_url"
                                            placeholder="https://search.google.com/local/writereview?placeid=..."
                                            value="{{ old('google_review_url') }}">
                                        @error('google_review_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">From Google Business Profile &rarr; "Ask for reviews" &rarr; copy link.</small>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="logo" class="form-label">Logo</label>
                                        <input
                                            type="file"
                                            class="form-control @error('logo') is-invalid @enderror"
                                            id="logo"
                                            name="logo"
                                            accept="image/png, image/jpeg, image/webp">
                                        @error('logo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <img id="logoPreview" class="mt-2 d-none" width="100" height="100" style="object-fit:cover;border-radius:6px;">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label d-block">Status</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">Active</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="hstack gap-2 justify-content-end mt-3">
                                        <button type="submit" class="btn btn-primary" id="submitShopBtn">Save Shop</button>
                                        <a href="{{ route('manage-google-reviews.index') }}" class="btn btn-soft-danger">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    document.getElementById('logo').addEventListener('change', function() {
        var preview = document.getElementById('logoPreview');
        if (!this.files || !this.files[0]) {
            preview.classList.add('d-none');
            return;
        }
        var reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('d-none');
        };
        reader.readAsDataURL(this.files[0]);
    });

    document.getElementById('shopCreateForm').addEventListener('submit', function() {
        var btn = document.getElementById('submitShopBtn');
        if (btn.disabled) return false;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving...';
    });
</script>
@endpush