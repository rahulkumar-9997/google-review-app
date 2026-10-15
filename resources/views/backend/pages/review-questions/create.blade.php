@extends('backend.layouts.master')
@section('title','Add Review Question')
@push('styles')
@endpush
@section('main-content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xxl-12 col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Add Question</h4>
                        <div class="flex-shrink-0">
                            <a href="{{ route('manage-google-reviews.questions.index', $shop) }}" class="btn btn-info">
                                <i class="ri-arrow-left-line align-bottom me-1"></i>
                                Back to List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('manage-google-reviews.questions.store', $shop) }}" method="POST" id="questionForm">
                            @csrf
                            @include('backend.pages.review-questions._form', ['shop' => $shop])
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
    document.getElementById('questionForm').addEventListener('submit', function () {
        var btn = document.getElementById('submitQuestionBtn');
        if (btn.disabled) return false;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving...';
    });
</script>
@endpush