<?php

namespace App\Services;

use App\Models\AbusingWord;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class ProfanityFilter
{
    /**
     * Default list of abusive / profanity words as baseline.
     */
    protected static array $defaultWords = [
        'abuse', 'abusive', 'asshole', 'bastard', 'bitch', 'bullshit',
        'crap', 'cunt', 'dick', 'fuck', 'fucking', 'motherfucker',
        'nigger', 'pussy', 'shit', 'slut', 'whore', 'idiot', 'stupid',
        'madarchod', 'behenchod', 'bhenchod', 'gaand', 'chutiya', 'harami',
        'kamina', 'saala', 'kutta', 'kamine', 'bhosdike'
    ];

    /**
     * Get active abusive words list (cached).
     */
    public static function getActiveWords(): array
    {
        try {
            return Cache::remember('abusive_words_list', 3600, function () {
                if (Schema::hasTable('abusing_words')) {
                    $dbWords = AbusingWord::where('is_active', true)->pluck('word')->toArray();
                    if (!empty($dbWords)) {
                        return array_values(array_unique(array_map('strtolower', array_merge(self::$defaultWords, $dbWords))));
                    }
                }
                return self::$defaultWords;
            });
        } catch (\Throwable $e) {
            return self::$defaultWords;
        }
    }

    /**
     * Clear abusive words cache.
     */
    public static function clearCache(): void
    {
        Cache::forget('abusive_words_list');
    }

    /**
     * Check if a given text or input array contains any abusive words.
     * Returns matching word string if found, or null if clean.
     */
    public static function findAbusiveWord(mixed $input): ?string
    {
        if (empty($input)) {
            return null;
        }

        $words = self::getActiveWords();
        if (empty($words)) {
            return null;
        }

        // If array, recursively check values
        if (is_array($input)) {
            foreach ($input as $key => $val) {
                // Skip system/password/file/image upload fields
                $keyLower = strtolower((string)$key);
                if (in_array($keyLower, ['_token', '_method', 'password', 'password_confirmation', 'file', 'image', 'photo', 'logo', 'thumbnail', 'gallery', 'avatar', 'banner'])) {
                    continue;
                }
                $found = self::findAbusiveWord($val);
                if ($found) {
                    return $found;
                }
            }
            return null;
        }

        if (!is_string($input) && !is_numeric($input)) {
            return null;
        }

        $text = (string)$input;
        if (trim($text) === '') {
            return null;
        }

        $cleanText = strtolower($text);

        foreach ($words as $word) {
            $wordLower = strtolower(trim($word));
            if ($wordLower === '' || strlen($wordLower) < 2) continue;

            // Check word boundary or substring match
            $pattern = '/\b' . preg_quote($wordLower, '/') . '\b/i';
            if (preg_match($pattern, $cleanText) || str_contains($cleanText, $wordLower)) {
                return $word;
            }
        }

        return null;
    }
}
