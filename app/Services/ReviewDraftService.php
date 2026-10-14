<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ReviewDraftService
{
    public function draft(string $shopName, array $answers): string
    {
        $response = Http::withToken(config('services.openai.key'))
            ->timeout(20)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => config('services.openai.model', 'gpt-4o-mini'),
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You write short, natural, first-person Google reviews (2-4 sentences) '
                            . 'based only on the customer\'s own selections below. Mention at most one or '
                            . 'two specific items or brands naturally — never dump the whole list into the '
                            . 'review. Do not invent facts that were not implied by the input. Keep the tone '
                            . 'casual and specific, not promotional or generic.',
                    ],
                    [
                        'role' => 'user',
                        'content' => $this->buildPrompt($shopName, $answers),
                    ],
                ],
                'temperature' => 0.7,
                'max_tokens' => 180,
            ]);

        if ($response->failed()) {
            Log::error('OpenAI review draft request failed: ' . $response->body());
            throw new \RuntimeException('Could not generate a draft right now.');
        }

        $text = trim((string) $response->json('choices.0.message.content', ''));

        if ($text === '') {
            throw new \RuntimeException('Received an empty draft.');
        }

        return $text;
    }

    private function buildPrompt(string $shopName, array $answers): string
    {
        $lines = [];
        $lines[] = "Business name: {$shopName} (a kitchenware and home appliances store)";

        if (!empty($answers['purchase_for'])) {
            $lines[] = 'Purchase purpose: ' . implode(', ', $answers['purchase_for']);
        }
        if (!empty($answers['items'])) {
            $lines[] = 'Items bought: ' . implode(', ', $answers['items']);
        }
        if (!empty($answers['brands'])) {
            $lines[] = 'Brand(s) bought: ' . implode(', ', $answers['brands']);
        }
        if (!empty($answers['instore_experience'])) {
            $lines[] = 'What stood out about the in-store experience: ' . implode(', ', $answers['instore_experience']);
        }
        if (!empty($answers['why_choose'])) {
            $lines[] = 'Why they chose this store: ' . implode(', ', $answers['why_choose']);
        }
        if (!empty($answers['recommend'])) {
            $lines[] = 'Likelihood to recommend to others: ' . implode(', ', $answers['recommend']);
        }
        if (!empty($answers['extra'])) {
            $lines[] = "Customer's own words (typed or spoken): \"{$answers['extra']}\"";
        }

        $lines[] = 'Write a short, genuine-sounding Google review a real customer in India would post, '
            . 'based only on the above. No hashtags, no mention that this was AI-written.';

        return implode("\n", $lines);
    }
}
