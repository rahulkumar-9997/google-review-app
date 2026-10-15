<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ReviewQuestion;
use App\Models\ReviewQuestionOption;
use App\Models\ReviewShop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReviewQuestionOptionController extends Controller
{
    public function index(ReviewShop $shop, ReviewQuestion $question)
    {
        $this->authorizeQuestion($shop, $question);
        $options = $question->options()->ordered()->get();
        return view('backend.pages.review-question-options.index', compact('shop', 'question', 'options'));
    }

    public function create(ReviewShop $shop, ReviewQuestion $question)
    {
        $this->authorizeQuestion($shop, $question);

        return view('backend.pages.review-question-options.create', compact('shop', 'question'));
    }

    public function store(Request $request, ReviewShop $shop, ReviewQuestion $question)
    {
        $this->authorizeQuestion($shop, $question);
        $data = $this->validated($request);

        try {
            $question->options()->create($data);
        } catch (\Throwable $e) {
            Log::error("Failed to create option for question #{$question->id}: " . $e->getMessage());

            return back()->withInput()->with('error', 'Something went wrong, please try again.');
        }

        return redirect()
            ->route('manage-google-reviews.questions.options.index', [$shop, $question])
            ->with('success', 'Option added successfully.');
    }

    public function edit(ReviewShop $shop, ReviewQuestion $question, ReviewQuestionOption $option)
    {
        $this->authorizeOption($shop, $question, $option);

        return view('backend.pages.review-question-options.edit', compact('shop', 'question', 'option'));
    }

    public function update(Request $request, ReviewShop $shop, ReviewQuestion $question, ReviewQuestionOption $option)
    {
        $this->authorizeOption($shop, $question, $option);

        $data = $this->validated($request);

        try {
            $option->update($data);
        } catch (\Throwable $e) {
            Log::error("Failed to update option #{$option->id}: " . $e->getMessage());

            return back()->withInput()->with('error', 'Something went wrong, please try again.');
        }

        return redirect()
            ->route('manage-google-reviews.questions.options.index', [$shop, $question])
            ->with('success', 'Option updated successfully.');
    }

    public function destroy(ReviewShop $shop, ReviewQuestion $question, ReviewQuestionOption $option)
    {
        $this->authorizeOption($shop, $question, $option);

        try {
            $option->delete();
        } catch (\Throwable $e) {
            Log::error("Failed to delete option #{$option->id}: " . $e->getMessage());

            return back()->with('error', 'Could not remove this option.');
        }

        return redirect()
            ->route('manage-google-reviews.questions.options.index', [$shop, $question])
            ->with('success', 'Option removed.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'option_text' => 'required|string|max:150',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active', true);

        return $data;
    }

    private function authorizeQuestion(ReviewShop $shop, ReviewQuestion $question): void
    {
        abort_unless($question->review_shop_id === $shop->id, 404);
    }

    private function authorizeOption(ReviewShop $shop, ReviewQuestion $question, ReviewQuestionOption $option): void
    {
        $this->authorizeQuestion($shop, $question);
        abort_unless($option->review_question_id === $question->id, 404);
    }
}
