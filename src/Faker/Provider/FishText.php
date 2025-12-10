<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Faker\Provider;

use Faker\Provider\Base;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class FishText extends Base
{
    public static function fishSentence(): string
    {
        return static::randomElement(static::fishQuery('sentence', 500, '. '));
    }

    /**
     * @return ($asText is true ? string : array)
     */
    public static function fishSentences(int $nb = 3, bool $asText = false): array|string
    {
        $sentences = static::randomElements(static::fishQuery('sentence', 500, '. '), $nb);

        return $asText ? implode(' ', $sentences) : $sentences;
    }

    public static function fishParagraph(): string
    {
        return static::randomElement(static::fishQuery('paragraph', 100, "\n\n"));
    }

    /**
     * @return ($asText is true ? string : array)
     */
    public static function fishParagraphs(int $nb = 3, bool $asText = false): array|string
    {
        $paragraphs = static::randomElements(static::fishQuery('paragraph', 100, "\n\n"), $nb);

        return $asText ? implode("\n\n", $paragraphs) : $paragraphs;
    }

    public static function fishTitle(): string
    {
        return static::randomElement(static::fishQuery('title', 500, "\n\n"));
    }

    /**
     * @return ($asText is true ? string : array)
     */
    public static function fishTitles(int $nb = 3, bool $asText = false): array|string
    {
        $titles = static::randomElements(static::fishQuery('title', 500, "\n\n"), $nb);

        return $asText ? implode("\n\n", $titles) : $titles;
    }

    /**
     * @return list<string>
     */
    protected static function fishQuery(string $type, int $number, string $delimiter): array
    {
        return Cache::remember('faker:fishtext:'.$type, 86400, static function () use ($type, $number, $delimiter) {
            return static::fishSplit(
                Http::get('https://fish-text.ru/get', compact('type', 'number'))->json('text'), $delimiter
            );
        });
    }

    protected static function fishSplit(string $text, string $delimiter): array
    {
        return str($text)
            ->explode($delimiter)
            ->map(static fn (string $str) => $str.trim($delimiter))
            ->all();
    }
}
