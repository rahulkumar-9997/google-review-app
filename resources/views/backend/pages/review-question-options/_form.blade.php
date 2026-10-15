@php
    $isEdit = isset($option);
@endphp

<div class="row">
    <div class="col-lg-8">
        <div class="mb-3">
            <label for="option_text" class="form-label">Option Text *</label>
            <input
                type="text"
                class="form-control @error('option_text') is-invalid @enderror"
                id="option_text"
                name="option_text"
                value="{{ old('option_text', $isEdit ? $option->option_text : '') }}">
            @error('option_text')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-lg-4">
        <div class="mb-3">
            <label for="sort_order" class="form-label">Order</label>
            <input
                type="number"
                min="0"
                class="form-control @error('sort_order') is-invalid @enderror"
                id="sort_order"
                name="sort_order"
                value="{{ old('sort_order', $isEdit ? $option->sort_order : 0) }}">
            @error('sort_order')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted">Where this option appears in the list — lower shows first.</small>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="mb-3">
            <label class="form-label d-block">Status</label>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', $isEdit ? $option->is_active : true) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Active</label>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-end mt-3">
    <button type="submit" class="btn btn-success" id="submitOptionBtn">{{ $isEdit ? 'Update Option' : 'Save Option' }}</button>
</div>