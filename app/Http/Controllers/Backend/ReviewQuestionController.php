<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ReviewQuestion;
use App\Models\ReviewQuestionOption;
use App\Models\ReviewShop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ReviewQuestionController extends Controller
{
    public function index(ReviewShop $shop)
    {
        $questions = $shop->questions()->withCount('options')->ordered()->get();

        return view('backend.pages.review-questions.index', compact('shop', 'questions'));
    }

    public function create(ReviewShop $shop)
    {
        return view('backend.pages.review-questions.create', compact('shop'));
    }

    public function store(Request $request, ReviewShop $shop)
    {
        $data = $this->validated($request);
        $data['review_shop_id'] = $shop->id;
        $data['key'] = $this->uniqueKey($shop->id, $data['question']);
        //dd($request);
        try {
            $question = ReviewQuestion::create($data);
            $this->syncOptions($question, $request->input('options', []), $request->input('option_ids', []));
        } catch (\Throwable $e) {
            Log::error("Failed to create review question for shop #{$shop->id}: " . $e->getMessage());

            return back()->withInput()->with('error', 'Something went wrong, please try again.');
        }

        return redirect()
            ->route('manage-google-reviews.questions.index', $shop)
            ->with('success', 'Question added successfully.');
    }

    public function edit(ReviewShop $shop, ReviewQuestion $question)
    {
        abort_unless($question->review_shop_id === $shop->id, 404);

        $question->load('options');

        return view('backend.pages.review-questions.edit', compact('shop', 'question'));
    }

    public function update(Request $request, ReviewShop $shop, ReviewQuestion $question)
    {
        abort_unless($question->review_shop_id === $shop->id, 404);

        // Key is NOT regenerated here — editing the question text doesn't
        // change it, since it may already be referenced elsewhere.
        $data = $this->validated($request);

        try {
            $question->update($data);
            $this->syncOptions($question, $request->input('options', []), $request->input('option_ids', []));
        } catch (\Throwable $e) {
            Log::error("Failed to update review question #{$question->id}: " . $e->getMessage());

            return back()->withInput()->with('error', 'Something went wrong, please try again.');
        }

        return redirect()
            ->route('manage-google-reviews.questions.index', $shop)
            ->with('success', 'Question updated successfully.');
    }

    public function destroy(ReviewShop $shop, ReviewQuestion $question)
    {
        abort_unless($question->review_shop_id === $shop->id, 404);

        try {
            $question->delete(); // options cascade via FK
        } catch (\Throwable $e) {
            Log::error("Failed to delete review question #{$question->id}: " . $e->getMessage());

            return back()->with('error', 'Could not remove this question.');
        }

        return redirect()
            ->route('manage-google-reviews.questions.index', $shop)
            ->with('success', 'Question removed.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'question' => 'required|string|max:255',
            'type' => 'required|in:single,multiple,text',
            'placeholder' => 'nullable|string|max:255',
            'max_select' => 'nullable|integer|min:1|max:20',
            'sort_order' => 'nullable|integer|min:0',
            'options' => ['nullable', 'array', function ($attribute, $value, $fail) use ($request) {
                if (in_array($request->input('type'), ['single', 'multi'], true)) {
                    $nonEmpty = collect($value ?? [])->filter(fn ($v) => trim((string) $v) !== '');
                    if ($nonEmpty->count() < 1) {
                        $fail('Add at least one answer option.');
                    }
                }
            }],
            'options.*' => 'nullable|string|max:150',
        ]);

        if ($data['type'] !== 'multi') {
            $data['max_select'] = null;
        }
        if ($data['type'] !== 'text') {
            $data['placeholder'] = null;
        }

        $data['is_required'] = $request->boolean('is_required');
        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        unset($data['options']);

        return $data;
    }

    private function syncOptions(ReviewQuestion $question, array $texts, array $ids): void
    {
        $keepIds = [];

        foreach ($texts as $i => $text) {
            $text = trim((string) $text);
            if ($text === '') {
                continue;
            }

            $id = $ids[$i] ?? null;
            $option = $id
                ? ReviewQuestionOption::where('id', $id)->where('review_question_id', $question->id)->first()
                : null;

            if ($option) {
                $option->update(['option_text' => $text, 'sort_order' => $i, 'is_active' => true]);
            } else {
                $option = $question->options()->create(['option_text' => $text, 'sort_order' => $i, 'is_active' => true]);
            }

            $keepIds[] = $option->id;
        }

        $question->options()->whereNotIn('id', $keepIds ?: [0])->delete();
    }

    /**
     * Keys only need to be unique within a shop's own question set.
     */
    private function uniqueKey(int $shopId, string $source, ?int $ignoreId = null): string
    {
        $base = Str::slug($source, '_') ?: 'question';
        $key = $base;
        $i = 1;

        while (
            ReviewQuestion::where('review_shop_id', $shopId)
                ->where('key', $key)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $key = $base . '_' . $i++;
        }

        return $key;
    }
}