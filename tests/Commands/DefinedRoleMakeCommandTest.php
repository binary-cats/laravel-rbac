<?php

namespace BinaryCats\LaravelRbac\Tests\Commands;

use BinaryCats\LaravelRbac\Commands\DefinedRoleMakeCommand;
use BinaryCats\LaravelRbac\Tests\TestCase;
use Illuminate\Support\Facades\File;
use PHPUnit\Framework\Attributes\After;
use PHPUnit\Framework\Attributes\Test;

class DefinedRoleMakeCommandTest extends TestCase
{
    #[After]
    protected function tearDown(): void
    {
        $stubPath = app_path('Roles/FooRole.php');

        if (File::exists($stubPath)) {
            unlink($stubPath);
        }

        parent::tearDown();
    }

    #[Test]
    public function it_will_return_the_name_of_the_stub_for_the_make_contract_command(): void
    {
        $stubPath = app_path('Roles/FooRole.php');

        $this->artisan(DefinedRoleMakeCommand::class, ['name' => 'FooRole'])
            ->assertOk();

        $this->assertFileExists($stubPath);
        $this->assertStringContainsString('class FooRole extends DefinedRole', File::get($stubPath));
        $this->assertStringContainsString('namespace App\Roles;', File::get($stubPath));
    }
}
