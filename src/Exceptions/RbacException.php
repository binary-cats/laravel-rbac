<?php

namespace BinaryCats\LaravelRbac\Exceptions;

use BackedEnum;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class RbacException extends Exception
{
    /**
     * @param \Illuminate\Support\Collection $abilities
     *
     * @return static
     */
    public static function rbacContainsDuplicateAbilities(Collection $abilities): static
    {
        $duplicates = $abilities
            ->groupBy('value')
            ->filter(fn ($element) => $element->count() > 1)
            ->collapse()
            ->map(
                fn (BackedEnum $enum) => Str::of(get_class($enum))
                ->classBasename()
                ->append(':', $enum->value)
            );

        $message = __('The following RBAC abilities are duplicated [:duplicates]', [
            'duplicates' => $duplicates->implode(', '),
        ]);

        return new static($message);
    }
}
