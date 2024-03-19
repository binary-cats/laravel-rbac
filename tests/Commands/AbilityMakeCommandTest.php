<?php

namespace BinaryCats\Rbac\Tests\Commands;

use BinaryCats\Rbac\Commands\AbilityMakeCommand;
use BinaryCats\Rbac\Tests\TestCase;
use Illuminate\Support\Facades\File;
use PHPUnit\Framework\Attributes\After;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Snapshots\MatchesSnapshots;

class AbilityMakeCommandTest extends TestCase
{
    #[After]
    protected function tearDown(): void
    {
        $stubPath = app_path('Abilities/FooAbility.php');

        if (File::exists($stubPath)) {
            unlink($stubPath);
        }

        parent::tearDown();
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
}
