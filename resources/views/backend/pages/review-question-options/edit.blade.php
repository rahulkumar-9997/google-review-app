@extends('backend.layouts.master')
@section('title','Edit Option')
@push('styles')
@endpush
@section('main-content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xxl-12 col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Edit Option — {{ $question->question }}</h4>
                        <div class="flex-shrink-0">
                            <a href="{{ route('manage-google-reviews.questions.options.index', [$shop, $question]) }}" class="btn btn-info">
                                <i class="ri-arrow-left-line align-bottom me-1"></i>
                                Back to List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('manage-google-reviews.questions.options.update', [$shop, $question, $option]) }}" method="POST" id="optionForm">
                            @csrf
                            @method('PUT')
                            @include('backend.pages.review-question-options._form', ['option' => $option])
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
    document.getElementById('optionForm').addEventListener('submit', function () {
        var btn = document.getElementById('submitOptionBtn');
        if (btn.disabled) return false;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Updating...';
    });
</script>
@endpush