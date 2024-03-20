<?php

namespace BinaryCats\Rbac\Tests\Exceptions;

use BinaryCats\Rbac\Exceptions\RbacException;
use BinaryCats\Rbac\Tests\Fixtures\AbilitiesWithDuplicateValues\BatDuplicatedAbility;
use BinaryCats\Rbac\Tests\Fixtures\AbilitiesWithDuplicateValues\FooDuplicatedAbility;
use BinaryCats\Rbac\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class RbacExceptionTest extends TestCase
{
    #[Test]
    public function it_will_render_a_duplicate_exception(): void
    {
        $list = collect([
            FooDuplicatedAbility::One,
            BatDuplicatedAbility::One,
            BatDuplicatedAbility::Tres,
        ]);

        $exception = RbacException::rbacContainsDuplicateAbilities($list);

        $this->assertInstanceOf(RbacException::class, $exception);
        $this->assertEquals(
            'The following RBAC abilities are duplicated [FooDuplicatedAbility:una, BatDuplicatedAbility:una]',
            $exception->getMessage()
        );
    }
}
