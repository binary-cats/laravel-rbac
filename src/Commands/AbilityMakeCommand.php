<?php

namespace BinaryCats\LaravelRbac\Commands;

use Illuminate\Console\Concerns\CreatesMatchingTest;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class AbilityMakeCommand extends GeneratorCommand
{
    use CreatesMatchingTest;

    /** @var string */
    protected $name = 'make:ability';

    /** @var string */
    protected $description = 'Create a new Ability enum';

    /** @var string */
    protected $type = 'Ability';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/../../stubs/ability.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Abilities';
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param string $stub
     * @param string $name
     *
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $subject = Str::of($name)
            ->classBasename()
            ->replaceLast($this->type, '');

        $stub = Str::of($stub)
            ->replace('{{ Subject }}', $subject)
            ->replace('{{ subject }}', $subject->lower())
            ->toString();

        return parent::replaceClass($stub, $name);
    }
}
