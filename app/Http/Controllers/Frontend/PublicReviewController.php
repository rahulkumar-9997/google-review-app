<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use App\Models\ReviewShop;
use App\Services\ReviewDraftService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PublicReviewController extends Controller
{
    public function show(string $slug)
    {
        $shop = ReviewShop::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('frontend.show', compact('shop'));
    }

    public function draft(Request $request, string $slug, ReviewDraftService $drafter)
    {
        $shop = ReviewShop::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
        $data = $request->validate([
            'purchase_for' => 'required|array|min:1',
            'purchase_for.*' => 'string|max:150',
            'items' => 'required|array|min:1',
            'items.*' => 'string|max:150',
            'brands' => 'required|array|min:1',
            'brands.*' => 'string|max:100',
            'instore_experience' => 'nullable|array',
            'instore_experience.*' => 'string|max:150',
            'why_choose' => 'nullable|array|max:2',
            'why_choose.*' => 'string|max:150',
            'recommend' => 'required|array|min:1',
            'recommend.*' => 'string|max:50',
            'extra' => 'nullable|string|max:500',
        ]);
        try {
            $text = $drafter->draft($shop->name, $data);
        } catch (\Throwable $e) {
            Log::error('Review draft failed for shop #' . $shop->id . ': ' . $e->getMessage());
            return response()->json(['message' => 'Could not generate a draft, please try again.'], 500);
        }
        return response()->json(['draft' => $text]);
    }
}