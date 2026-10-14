@extends('backend.layouts.master')
@section('title','Dashboard')
@push('styles')
@endpush
@section('main-content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="h-100">
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <a href="">
                                <div class="card card-animate">
                                    <div class="card-body d-flex gap-3 align-items-center">
                                        <div class="avatar-sm">
                                            <div class="avatar-title border bg-warning-subtle border-warning border-opacity-25 rounded-2 fs-17">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text icon-dual-warning">
                                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                    <polyline points="14 2 14 8 20 8"></polyline>
                                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                                    <polyline points="10 9 9 9 8 9"></polyline>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h5 class="fs-15">0</h5>
                                            <p class="mb-0 text-muted">Total Google Reviews</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection