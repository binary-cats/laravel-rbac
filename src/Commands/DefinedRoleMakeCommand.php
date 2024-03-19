<?php

namespace BinaryCats\Rbac\Commands;

use Illuminate\Console\Concerns\CreatesMatchingTest;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class DefinedRoleMakeCommand extends GeneratorCommand
{
    use CreatesMatchingTest;

    /** @var string  */
    protected $name = 'make:role';

    /** @var string  */
    protected $description = 'Create a new DefinedRole class';

    /** @var string  */
    protected $type = 'DefinedRole';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/stubs/defined-role.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Roles';
    }
}
