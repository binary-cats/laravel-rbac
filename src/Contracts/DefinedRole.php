<?php

namespace BinaryCats\LaravelRbac\Contracts;

interface DefinedRole
{
    /**
     * Name of the defined role.
     */
    public function name(): string;

    /**
     * Handle the role definition.
     */
    public function handle(): void;
}
