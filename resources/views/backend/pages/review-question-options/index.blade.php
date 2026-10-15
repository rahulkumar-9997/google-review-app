@extends('backend.layouts.master')
@section('title','Question Options')
@push('styles')
@endpush
@section('main-content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xxl-12 col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <div class="flex-grow-1">
                            <h4 class="card-title mb-1">Options — {{ $question->question }}</h4>
                            <a href="{{ route('manage-google-reviews.questions.index', $shop) }}" class="text-muted small">
                                <i class="ri-arrow-left-line align-bottom"></i> Back to Questions — {{ $shop->name }}
                            </a>
                        </div>
                        <div class="flex-shrink-0">
                            <a href="{{ route('manage-google-reviews.questions.options.create', [$shop, $question]) }}" class="btn btn-primary">
                                <i class="ri-add-line align-bottom me-1"></i>
                                Add Option
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-nowrap align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:70px;">Order</th>
                                        <th>Option Text</th>
                                        <th>Status</th>
                                        <th style="width:140px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($options as $option)
                                        <tr>
                                            <td><span class="badge bg-light text-dark">{{ $option->sort_order }}</span></td>
                                            <td>{{ $option->option_text }}</td>
                                            <td>
                                                <span class="badge {{ $option->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $option->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="hstack gap-2">
                                                    <a href="{{ route('manage-google-reviews.questions.options.edit', [$shop, $question, $option]) }}" class="btn btn-sm btn-soft-primary">
                                                        <i class="ri-pencil-line align-bottom"></i>
                                                    </a>
                                                    <form action="{{ route('manage-google-reviews.questions.options.destroy', [$shop, $question, $option]) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-soft-danger show_confirm" data-name="option">
                                                            <i class="ri-delete-bin-line align-bottom"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">No options added yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection