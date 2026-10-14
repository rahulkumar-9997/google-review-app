<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ReviewShop;
use App\Helpers\ImageHelper;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class GoogleReviewController extends Controller
{
    private const LOGO_FOLDER = 'google-review';
    public function index()
    {
        $shops = ReviewShop::with('user')->latest()->paginate(10);
        return view('backend.pages.google-reviews.index', compact('shops'));
    }

    public function create()
    {
        return view('backend.pages.google-reviews.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['name']);
        $data['user_id'] = auth()->id();

        if ($request->hasFile('logo')) {
            $filename = ImageHelper::generateFileName($data['name']);
            $data['logo'] = ImageHelper::uploadSingleImageWebpOnly($request->file('logo'), $filename, self::LOGO_FOLDER);
        }

        try {
            ReviewShop::create($data);
        } catch (\Throwable $e) {
            Log::error('Failed to create review shop: ' . $e->getMessage());

            return back()->withInput()->with('error', 'Something went wrong, please try again.');
        }

        return redirect()
            ->route('manage-google-reviews.index')
            ->with('success', 'Shop added successfully.');
    }

    public function edit(ReviewShop $manage_google_review)
    {
        $shop = $manage_google_review;
        return view('backend.pages.google-reviews.edit', compact('shop'));
    }

    
    public function update(Request $request, ReviewShop $manage_google_review)
    {
        $shop = $manage_google_review;
        $data = $this->validated($request);
        try {
            if ($request->hasFile('logo')) {
                $filename = ImageHelper::generateFileName($data['name']);
                $data['logo'] = ImageHelper::uploadSingleImageWebpOnly(
                    $request->file('logo'),
                    $filename,
                    self::LOGO_FOLDER,
                    $shop->logo
                );
            }
            $shop->update($data);
        } catch (\Throwable $e) {
            Log::error("Failed to update review shop #{$shop->id}: " . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Something went wrong, please try again.');
        }

        return redirect()
            ->route('manage-google-reviews.index')
            ->with('success', 'Shop updated successfully.');
    }


    public function destroy(ReviewShop $shop)
    {
        try {
            if ($shop->logo) {
                ImageHelper::deleteSingleImage($shop->logo, self::LOGO_FOLDER);
            }
            $shop->delete();
        } catch (\Throwable $e) {
            Log::error("Failed to delete review shop #{$shop->id}: " . $e->getMessage());

            return back()->with('error', 'Could not remove this shop.');
        }

        return redirect()
            ->route('manage-google-reviews.index')
            ->with('success', 'Shop removed.');
    }

    public function qrcode(ReviewShop $manage_google_review)
    {
        $shop = $manage_google_review;

        $url = route('review.show', $shop->slug);
        $result = (new Builder())->build(
            writer: new PngWriter(),
            data: $url,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 400,
            margin: 10,
        );

        return response($result->getString(), 200)
            ->header('Content-Type', $result->getMimeType())
            ->header('Content-Disposition', 'attachment; filename="qr-' . $shop->slug . '.png"');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'google_review_url' => 'nullable|url',
            'logo' => 'nullable|image|max:2048',
        ]);
        $data['is_active'] = $request->boolean('is_active', true);
        return $data;
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;

        while (ReviewShop::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
