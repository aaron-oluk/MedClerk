<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Tag;
use Illuminate\Support\Str;

trait SyncsTags
{
    protected function syncTagsFromInput($model, ?string $input): void
    {
        if (blank($input)) {
            return;
        }

        $tagIds = collect(explode(',', $input))
            ->map(fn ($name) => trim($name))
            ->filter()
            ->unique()
            ->map(function ($name) {
                $tag = Tag::firstOrCreate(
                    ['slug' => Str::slug($name)],
                    ['name' => $name]
                );

                return $tag->id;
            });

        $model->tags()->sync($tagIds);
    }
}
