@extends('backend.layouts.master')
@section('title','Review Questions')
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
                            <h4 class="card-title mb-1">Review Questions — {{ $shop->name }}</h4>
                            <a href="{{ route('manage-google-reviews.index') }}" class="text-muted small">
                                <i class="ri-arrow-left-line align-bottom"></i> Back to Shops
                            </a>
                        </div>
                        <div class="flex-shrink-0">
                            <a href="{{ route('manage-google-reviews.questions.create', $shop) }}" class="btn btn-primary">
                                <i class="ri-add-line align-bottom me-1"></i>
                                Add Question
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
                                        <th style="width:70px;">Step</th>
                                        <th>Question</th>
                                        <th>Key</th>
                                        <th>Type</th>
                                        <th>Required</th>
                                        <th>Options</th>
                                        <th>Status</th>
                                        <th style="width:140px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($questions as $question)
                                        <tr>
                                            <td><span class="badge bg-light text-dark">{{ $question->sort_order }}</span></td>
                                            <td>{{ $question->question }}</td>
                                            <td><code>{{ $question->key }}</code></td>
                                            <td>
                                                @php
                                                    $typeLabel = match ($question->type) {
                                                        'single' => 'Radio button',
                                                        'text' => 'Text area',
                                                        default => 'Checkbox',
                                                    };
                                                @endphp
                                                <span class="badge bg-info-subtle text-info text-uppercase">{{ $typeLabel }}</span>
                                                @if ($question->type === 'multi' && $question->max_select)
                                                    <span class="badge bg-light text-dark">max {{ $question->max_select }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $question->is_required ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $question->is_required ? 'Required' : 'Optional' }}
                                                </span>
                                            </td>
                                            <td>{{ $question->type === 'text' ? '—' : $question->options_count }}</td>
                                            <td>
                                                <span class="badge {{ $question->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $question->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="hstack gap-2">
                                                    @if ($question->type !== 'text')
                                                        <a href="{{ route('manage-google-reviews.questions.options.index', [$shop, $question]) }}" class="btn btn-sm btn-soft-secondary" title="Manage options">
                                                            <i class="ri-list-check-2 align-bottom"></i>
                                                        </a>
                                                    @endif
                                                    <a href="{{ route('manage-google-reviews.questions.edit', [$shop, $question]) }}" class="btn btn-sm btn-soft-primary">
                                                        <i class="ri-pencil-line align-bottom"></i>
                                                    </a>
                                                    <form action="{{ route('manage-google-reviews.questions.destroy', [$shop, $question]) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-soft-danger show_confirm" data-name="question">
                                                            <i class="ri-delete-bin-line align-bottom"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted py-4">No questions added yet.</td>
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