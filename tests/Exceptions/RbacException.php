<?php

namespace BinaryCats\Rbac\Tests\Exceptions;

use BackedEnum;
use Exception;
use Illuminate\Support\Str;

class RbacException extends Exception
{
    public static function rbacContainsDuplicateAbilities($abilities): static
    {
        $duplicates = $abilities
            ->groupBy('value')
            ->filter(fn($element) => $element->count() > 2)
            ->map(fn(BackedEnum $enum) => Str::of(get_class($enum))->append(':', $enum->value));

        $message = __('The following RBAC abilities are duplicated [:duplicates]', [
            'duplicates' => $duplicates->implode(', ')
        ]);

        return new static($message);
    }
}
