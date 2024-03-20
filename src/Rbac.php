<?php

namespace BinaryCats\Rbac;

use Illuminate\Support\Collection;

class Rbac
{
    /** @var string  */
    protected string $abilitiesPath;

    /** @var string  */
    protected string $basePath;

    /** @var \Illuminate\Support\Collection|null  */
    protected ?Collection $abilities = null;

    /**
     * @param string $abilitiesPath
     * @param string $basePath
     */
    public function __construct(string $abilitiesPath, string $basePath)
    {
        $this->abilitiesPath = $abilitiesPath;
        $this->basePath = $basePath;
    }

    /**
     * Return the list of all abilities in the application
     */
    public function abilities(): Collection
    {
        if (null === $this->abilities) {
            $this->abilities = $this->discoverAbilities();
        }

        return $this->abilities;
    }

    /**
     * Discover Abilities in path
     */
    protected function discoverAbilities(): Collection
    {
        return DiscoverAbilities::within(
            abilitiesPath: $this->abilitiesPath,
            basePath: $this->basePath
        );
    }
}
