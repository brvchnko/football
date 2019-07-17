<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\Factory\TokenFactory;
use App\Tests\DataFixtures\Entity\UserData;
use App\Tests\DataFixtures\Model\Token\TokenData;
use PHPUnit\Framework\TestCase;

class TokenFactoryTest extends TestCase
{
    /** @var TokenFactory */
    private $factory;

    protected function setUp()
    {
        $this->factory = new TokenFactory();
    }

    /**
     * @test
     */
    public function willCreateDataModel(): void
    {
        $entity = UserData::get();
        $tokenId = 'testId';
        $tokenExpiry = 120;

        $result = $this->factory->create($entity, $tokenId, $tokenExpiry);

        $this->assertEquals(TokenData::get($entity, $tokenId, $tokenExpiry), $result);
    }
}
