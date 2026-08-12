<?php

namespace BinaryCats\LaravelRbac\Tests\Commands;

use BinaryCats\LaravelRbac\Commands\AbilityMakeCommand;
use BinaryCats\LaravelRbac\Tests\TestCase;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use PHPUnit\Framework\Attributes\After;
use PHPUnit\Framework\Attributes\Test;

class AbilityMakeCommandTest extends TestCase
{
    #[After]
    protected function cleanUpTest(): void
    {
        $stubPath = app_path('Abilities/FooAbility.php');

        if (File::exists($stubPath)) {
            unlink($stubPath);
        }
    }

    #[Test]
    public function it_will_return_the_name_of_the_stub_for_the_make_contract_command(): void
    {
        $stubPath = app_path('Abilities/FooAbility.php');

        $this->artisan(AbilityMakeCommand::class, ['name' => 'FooAbility'])
            ->assertOk();

        $this->assertFileExists($stubPath);
        $this->assertStringContainsString('enum FooAbility', File::get($stubPath));
        $this->assertStringContainsString('namespace App\Abilities;', File::get($stubPath));
        $this->assertStringContainsString("case ViewFoo = 'view foo';", File::get($stubPath));
        $this->assertStringContainsString("case CreateFoo = 'create foo';", File::get($stubPath));
        $this->assertStringContainsString("case UpdateFoo = 'update foo';", File::get($stubPath));
        $this->assertStringContainsString("case DeleteFoo = 'delete foo';", File::get($stubPath));
    }

    #[Test]
    public function it_will_use_a_custom_ability_stub_when_available(): void
    {
        $basePath = sys_get_temp_dir().'/laravel-rbac-'.uniqid();
        $customStubPath = $basePath.'/stubs/ability.stub';

        File::ensureDirectoryExists(dirname($customStubPath));
        File::put($customStubPath, 'custom ability stub');

        try {
            $this->assertSame($customStubPath, $this->commandForBasePath($basePath)->stub());
        } finally {
            File::deleteDirectory($basePath);
        }
    }

    #[Test]
    public function it_will_use_the_package_ability_stub_when_a_custom_stub_is_unavailable(): void
    {
        $basePath = sys_get_temp_dir().'/laravel-rbac-'.uniqid();

        try {
            $this->assertSame(
                realpath(dirname(__DIR__, 2).'/stubs/ability.stub'),
                realpath($this->commandForBasePath($basePath)->stub())
            );
        } finally {
            File::deleteDirectory($basePath);
        }
    }

    private function commandForBasePath(string $basePath): AbilityMakeCommand
    {
        $application = $this->createMock(Application::class);
        $application->expects($this->once())
            ->method('basePath')
            ->willReturnCallback(fn (string $path = ''): string => $basePath.$path);

        $command = new class(app(Filesystem::class)) extends AbilityMakeCommand {
            public function stub(): string
            {
                return $this->getStub();
            }
        };

        $command->setLaravel($application);

        return $command;
    }
}
