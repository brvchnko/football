<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\Factory\UserFactory;
use App\Tests\DataFixtures\Entity\UserData;
use PHPUnit\Framework\TestCase;

class UserFactoryTest extends TestCase
{
    /** @var UserFactory */
    private $factory;

    protected function setUp()
    {
        $this->factory = new UserFactory();
    }

    /**
     * @test
     */
    public function willAddProvidedRoles(): void
    {
        $entity = UserData::get();

        $result = $this->factory->updateUserRoles($entity, ['ROLE_TEST']);

        $this->assertSame($entity->setRoles(['ROLE_TEST']), $result);
    }

    /**
     * @test
     */
    public function willAddDefaultRoles(): void
    {
        $entity = UserData::get();

        $result = $this->factory->updateUserRoles($entity);

        $this->assertSame($entity->setRoles(['ROLE_USER', 'ROLE_ADMIN']), $result);
    }
}
