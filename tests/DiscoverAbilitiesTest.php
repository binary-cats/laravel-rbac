<?php

namespace BinaryCats\LaravelRbac\Tests;

use BinaryCats\LaravelRbac\DiscoverAbilities;
use BinaryCats\LaravelRbac\Exceptions\RbacException;
use BinaryCats\LaravelRbac\Tests\Fixtures\Abilities\FooAbility;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use SplFileInfo;

class DiscoverAbilitiesTest extends TestCase
{
    #[Test]
    public function it_will_handle_class_from_file_resolution_closure_callback(): void
    {
        DiscoverAbilities::guessClassNamesUsing(function (SplFileInfo $file, $basePath) {
            return Str::of($file->getRealPath())
                ->replaceFirst($basePath, '')
                ->trim(DIRECTORY_SEPARATOR)
                ->after('/tests/')
                ->prepend('BinaryCats/LaravelRbac/Tests/')
                ->replaceLast('.php', '')
                ->replace(DIRECTORY_SEPARATOR, '\\')
                ->toString();
        });

        $result = DiscoverAbilities::within(
            __DIR__.'/Fixtures/Abilities',
            $this->app->basePath()
        );

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);
        $this->assertContains(FooAbility::One, $result);
        $this->assertContains(FooAbility::Two, $result);
    }

    #[Test]
    public function it_will_discover_the_abilities_in_a_given_path(): void
    {
        DiscoverAbilities::$guessClassNamesUsingCallback = null;

        $result = DiscoverAbilities::within(
            __DIR__.'/Fixtures/Abilities',
            __DIR__.'/../'
        );

        $this->assertCount(0, $result);
    }

    #[Test]
    public function it_will_throw_a_duplicate_exception_when_there_is_a_duplicate_permission(): void
    {
        DiscoverAbilities::guessClassNamesUsing(function (SplFileInfo $file, $basePath) {
            return Str::of($file->getRealPath())
                ->replaceFirst($basePath, '')
                ->trim(DIRECTORY_SEPARATOR)
                ->after('/tests/')
                ->prepend('BinaryCats/LaravelRbac/Tests/')
                ->replaceLast('.php', '')
                ->replace(DIRECTORY_SEPARATOR, '\\')
                ->toString();
        });

        $this->expectException(RbacException::class);

        DiscoverAbilities::within(
            __DIR__.'/Fixtures/AbilitiesWithDuplicateValues',
            __DIR__.'/../'
        );
    }
}
