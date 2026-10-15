@php
    $isEdit = isset($question);
    $options = $isEdit ? $question->options : collect();
    $currentType = old('type', $isEdit ? $question->type : 'multi');
    $submitLabel = $submitLabel ?? ($isEdit ? 'Update Question' : 'Save Question');

    // Land the admin on whichever wizard step actually has the validation
    // error after a failed submit — step 2 (Answer Options) is hidden by
    // default, so an 'options' error would otherwise be invisible.
    $hasStep1Errors = $errors->hasAny(['question', 'type', 'placeholder', 'max_select', 'sort_order']);
    $initialStep = (!$hasStep1Errors && $errors->has('options')) ? 2 : 1;
@endphp

<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="fw-semibold" id="wizardStepLabel">Step 1 of 2 — Question Details</span>
        <span class="text-muted small" id="wizardStepHint">Next: Answer Options</span>
    </div>
    <div class="progress" style="height:6px;">
        <div class="progress-bar bg-primary" id="wizardProgressBar" role="progressbar" style="width:50%"></div>
    </div>
</div>

<div id="wizardStep1">
    <div class="row">
        <div class="col-lg-12">
            <div class="mb-3">
                <label for="question" class="form-label">Question *</label>
                <input
                    type="text"
                    class="form-control @error('question') is-invalid @enderror"
                    id="question"
                    name="question"
                    value="{{ old('question', $isEdit ? $question->question : '') }}">
                @error('question')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-lg-4">
            <div class="mb-3">
                <label for="type" class="form-label">Answer Type *</label>
                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type">
                    <option value="multiple" {{ $currentType === 'multiple' ? 'selected' : '' }}>Checkbox — multiple select (pick several)</option>
                    <option value="single" {{ $currentType === 'single' ? 'selected' : '' }}>Radio button — single select (pick one)</option>
                    <option value="text" {{ $currentType === 'text' ? 'selected' : '' }}>Text area — free text / voice note</option>
                </select>
                @error('type')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-lg-4" id="maxSelectWrap">
            <div class="mb-3">
                <label for="max_select" class="form-label">Max selections <small class="text-muted">(checkbox only, optional)</small></label>
                <input
                    type="number"
                    min="1"
                    max="20"
                    class="form-control @error('max_select') is-invalid @enderror"
                    id="max_select"
                    name="max_select"
                    value="{{ old('max_select', $isEdit ? $question->max_select : '') }}">
                @error('max_select')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-lg-4" id="placeholderWrap">
            <div class="mb-3">
                <label for="placeholder" class="form-label">Placeholder text <small class="text-muted">(text area only)</small></label>
                <input
                    type="text"
                    class="form-control @error('placeholder') is-invalid @enderror"
                    id="placeholder"
                    name="placeholder"
                    placeholder="e.g. a staff member's name, a product detail..."
                    value="{{ old('placeholder', $isEdit ? $question->placeholder : '') }}">
                @error('placeholder')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-lg-4">
            <div class="mb-3">
                <label for="sort_order" class="form-label">Step Number</label>
                <input
                    type="number"
                    min="0"
                    class="form-control @error('sort_order') is-invalid @enderror"
                    id="sort_order"
                    name="sort_order"
                    value="{{ old('sort_order', $isEdit ? $question->sort_order : 0) }}">
                @error('sort_order')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Where this question appears in the customer's flow — Step 1 shows first.</small>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="mb-3">
                <label class="form-label d-block">Required</label>
                <div class="form-check form-switch">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        role="switch"
                        id="is_required"
                        name="is_required"
                        value="1"
                        {{ old('is_required', $isEdit ? $question->is_required : false) ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="is_required">
                        Customer must answer
                    </label>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="mb-3">
                <label class="form-label d-block">Status</label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', $isEdit ? $question->is_active : true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Active</label>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end mt-3">
        <button type="button" class="btn btn-primary" id="wizardNextBtn">
            Next: Answer Options <i class="ri-arrow-right-line align-bottom ms-1"></i>
        </button>
    </div>
</div>

<div id="wizardStep2" class="d-none">
    <div class="row">
        <div class="col-lg-12" id="optionsSection">
            <label class="form-label d-block">Answer Options *</label>
            <div id="optionRows">
                @forelse ($options as $option)
                    <div class="input-group mb-2 option-row">
                        <input type="hidden" name="option_ids[]" value="{{ $option->id }}">
                        <input type="text" class="form-control" name="options[]" value="{{ $option->option_text }}" placeholder="Option text">
                        <button type="button" class="btn btn-outline-danger remove-option-row"><i class="ri-close-line"></i></button>
                    </div>
                @empty
                    <div class="input-group mb-2 option-row">
                        <input type="hidden" name="option_ids[]" value="">
                        <input type="text" class="form-control" name="options[]" placeholder="Option text">
                        <button type="button" class="btn btn-outline-danger remove-option-row"><i class="ri-close-line"></i></button>
                    </div>
                @endforelse
            </div>
            <button type="button" class="btn btn-sm btn-soft-primary" id="addOptionRow">
                <i class="ri-add-line align-bottom"></i> Add Option
            </button>
            @error('options')
            <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-lg-12 d-none" id="textPreview">
            <label class="form-label d-block">Preview</label>
            <textarea class="form-control" rows="3" disabled placeholder="Customer types or speaks their answer here..."></textarea>
            <small class="text-muted d-block mt-2">This question type has no answer options to set up — the customer types or speaks their own text.</small>
        </div>
    </div>

    <div class="d-flex justify-content-between mt-4">
        <button type="button" class="btn btn-soft-secondary" id="wizardBackBtn">
            <i class="ri-arrow-left-line align-bottom me-1"></i> Back
        </button>
        <div class="hstack gap-2">
            <a href="{{ route('manage-google-reviews.questions.index', $shop) }}" class="btn btn-soft-danger">Cancel</a>
            <button type="submit" class="btn btn-success" id="submitQuestionBtn">{{ $submitLabel }}</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    (function () {
        var typeSelect = document.getElementById('type');
        var maxSelectWrap = document.getElementById('maxSelectWrap');
        var placeholderWrap = document.getElementById('placeholderWrap');
        var placeholderInput = document.getElementById('placeholder');
        var optionsSection = document.getElementById('optionsSection');
        var textPreview = document.getElementById('textPreview');
        var optionRows = document.getElementById('optionRows');
        var addBtn = document.getElementById('addOptionRow');

        function setGroupDisabled(wrap, disabled) {
            wrap.classList.toggle('d-none', disabled);
            wrap.querySelectorAll('input').forEach(function (el) { el.disabled = disabled; });
        }

        function syncPreviewPlaceholder() {
            var area = textPreview.querySelector('textarea');
            if (area) area.placeholder = placeholderInput.value || 'Customer types or speaks their answer here...';
        }

        function applyType() {
            var type = typeSelect.value;
            setGroupDisabled(maxSelectWrap, type !== 'multi');
            setGroupDisabled(placeholderWrap, type !== 'text');
            setGroupDisabled(optionsSection, type === 'text');
            textPreview.classList.toggle('d-none', type !== 'text');
            if (type === 'text') syncPreviewPlaceholder();
        }

        function newRow() {
            var row = document.createElement('div');
            row.className = 'input-group mb-2 option-row';
            row.innerHTML = '<input type="hidden" name="option_ids[]" value="">'
                + '<input type="text" class="form-control" name="options[]" placeholder="Option text">'
                + '<button type="button" class="btn btn-outline-danger remove-option-row"><i class="ri-close-line"></i></button>';
            return row;
        }

        addBtn.addEventListener('click', function () {
            optionRows.appendChild(newRow());
        });

        optionRows.addEventListener('click', function (e) {
            var btn = e.target.closest('.remove-option-row');
            if (!btn) return;
            if (optionRows.querySelectorAll('.option-row').length > 1) {
                btn.closest('.option-row').remove();
            } else {
                btn.closest('.option-row').querySelector('input[name="options[]"]').value = '';
            }
        });

        typeSelect.addEventListener('change', applyType);
        placeholderInput.addEventListener('input', syncPreviewPlaceholder);
        applyType();

        // --- Two-step wizard: Question Details -> Answer Options -------
        var step1 = document.getElementById('wizardStep1');
        var step2 = document.getElementById('wizardStep2');
        var nextBtn = document.getElementById('wizardNextBtn');
        var backBtn = document.getElementById('wizardBackBtn');
        var progressBar = document.getElementById('wizardProgressBar');
        var stepLabel = document.getElementById('wizardStepLabel');
        var stepHint = document.getElementById('wizardStepHint');
        var questionInput = document.getElementById('question');

        function goToStep(n) {
            step1.classList.toggle('d-none', n !== 1);
            step2.classList.toggle('d-none', n !== 2);
            progressBar.style.width = (n === 1 ? '50%' : '100%');
            stepLabel.textContent = n === 1 ? 'Step 1 of 2 — Question Details' : 'Step 2 of 2 — Answer Options';
            stepHint.textContent = n === 1 ? 'Next: Answer Options' : 'Review and save';
        }

        nextBtn.addEventListener('click', function () {
            if (!questionInput.value.trim()) {
                questionInput.classList.add('is-invalid');
                questionInput.focus();
                return;
            }
            questionInput.classList.remove('is-invalid');
            goToStep(2);
        });

        backBtn.addEventListener('click', function () {
            goToStep(1);
        });
        goToStep({{ $initialStep }});
    })();
</script>
@endpush