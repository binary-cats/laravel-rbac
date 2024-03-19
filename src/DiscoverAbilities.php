<?php

namespace BinaryCats\Rbac;

use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionException;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

class DiscoverAbilities
{
    /**
     * The callback to be used to guess class names.
     *
     * @var callable(SplFileInfo, string): string|null
     */
    public static $guessClassNamesUsingCallback;

    /**
     * Discover all Ability enums
     *
     * @param string $abilitiesPath
     * @param string $basePath
     * @return \Illuminate\Support\Collection
     */
    public static function within(string $abilitiesPath, string $basePath)
    {
        $abilities = collect(static::getAbilities(
            Finder::create()->files()->in($abilitiesPath), $basePath
        ));

        return $abilities;
    }

    protected static function getAbilities($abilities, $basePath)
    {
        $enums = [];

        foreach ($abilities as $ability)
        {
            try {
                $ability = new ReflectionClass(
                    static::classFromFile($ability, $basePath)
                );
            } catch (ReflectionException) {
                continue;
            }

            if (! $ability->isEnum()) {
                continue;
            }

            foreach($ability->getReflectionConstants() as $permission) {
                $enums[] =  $ability->name::{$permission->getName()};
            }
        }

        return $enums;
    }

    /**
     * Extract the class name from the given file path.
     *
     * @param  \SplFileInfo  $file
     * @param  string  $basePath
     * @return string
     */
    protected static function classFromFile(SplFileInfo $file, $basePath)
    {
        if (static::$guessClassNamesUsingCallback) {
            return call_user_func(static::$guessClassNamesUsingCallback, $file, $basePath);
        }

        $class = trim(Str::replaceFirst($basePath, '', $file->getRealPath()), DIRECTORY_SEPARATOR);

        return str_replace(
            [DIRECTORY_SEPARATOR, ucfirst(basename(app()->path())).'\\'],
            ['\\', app()->getNamespace()],
            ucfirst(Str::replaceLast('.php', '', $class))
        );
    }

    /**
     * Specify a callback to be used to guess class names.
     *
     * @param  callable(SplFileInfo, string): string  $callback
     * @return void
     */
    public static function guessClassNamesUsing(callable $callback)
    {
        static::$guessClassNamesUsingCallback = $callback;
    }
}
