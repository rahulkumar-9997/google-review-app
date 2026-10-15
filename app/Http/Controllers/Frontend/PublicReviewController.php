<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ReviewShop;
use App\Services\ReviewDraftService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class PublicReviewController extends Controller
{
    public function show(string $slug)
    {
        $shop = $this->resolveShop($slug);
        $questions = $shop->questions()->active()->with('activeOptions')->get();
        $steps = $questions->map(function ($question) {
            $step = [
                'id' => $question->key,
                'type' => $question->type,
                'min' => $question->is_required ? 1 : 0,
                'question' => $question->question,
            ];
            if ($question->type === 'multi' && $question->max_select) {
                $step['max'] = $question->max_select;
                $step['hint'] = '(select up to ' . $question->max_select . ')';
            } elseif (! $question->is_required) {
                $step['hint'] = '(optional)';
            }
            if ($question->type === 'text') {
                $step['placeholder'] = $question->placeholder ?: 'Type your answer here...';
            } else {
                $step['options'] = $question->activeOptions->pluck('option_text')->values()->all();
            }
            return $step;
        })->values();
        //dd($steps);
        return view('frontend.show', compact('shop', 'steps'));
    }

    public function draft(Request $request, string $slug, ReviewDraftService $drafter)
    {
        $shop = $this->resolveShop($slug);
        $questions = $shop->questions()->active()->with('activeOptions')->get();
        $rules = [];
        foreach ($questions as $question) {
            $key = $question->key;
            $required = $question->is_required;
            if ($question->type === 'text') {
                $rules[$key] = [$required ? 'required' : 'nullable', 'string', 'max:500'];
                continue;
            }
            $optionTexts = $question->activeOptions->pluck('option_text')->all();
            if ($question->type === 'single') {
                $rules[$key] = [$required ? 'required' : 'nullable', 'string', Rule::in($optionTexts)];
                continue;
            }
            $arrayRule = [$required ? 'required' : 'nullable', 'array'];
            if ($required) {
                $arrayRule[] = 'min:1';
            }
            if ($question->max_select) {
                $arrayRule[] = 'max:' . $question->max_select;
            }

            $rules[$key] = $arrayRule;
            $rules["{$key}.*"] = ['string', 'max:150', Rule::in($optionTexts)];
        }

        $data = $request->validate($rules);
        try {
            $text = $drafter->draft($shop->name, $data);
        } catch (\Throwable $e) {
            Log::error('Review draft failed for shop #' . $shop->id . ': ' . $e->getMessage());

            return response()->json(['message' => 'Could not generate a draft, please try again.'], 500);
        }
        return response()->json(['draft' => $text]);
    }

    private function resolveShop(string $slug): ReviewShop
    {
        return ReviewShop::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
    }
}
