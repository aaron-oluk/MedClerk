<?php

namespace App\Http\Controllers\Concerns;

trait ParsesDelimitedInput
{
    /**
     * One item per line, e.g. media URLs or red flags typed into a textarea.
     */
    protected function linesToArray(?string $value): array
    {
        if (blank($value)) {
            return [];
        }

        return collect(preg_split('/\r\n|\r|\n/', $value))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }

    /**
     * Comma separated items, e.g. competency codes or equipment typed into a single line.
     */
    protected function commaToArray(?string $value): array
    {
        if (blank($value)) {
            return [];
        }

        return collect(explode(',', $value))
            ->map(fn ($item) => trim($item))
            ->filter()
            ->values()
            ->all();
    }
}
