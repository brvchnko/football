<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();

        $user
            ->setUsername('admin')
            ->setUsernameCanonical('admin')
            ->setPlainPassword('admin')
            ->setEmail('admin@admin.com')
            ->setEmailCanonical('admin@admin.com')
            ->setEnabled(true)
            ->setRoles(['ROLE_ADMIN']);

        $password = $this->encoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);

        $manager->persist($user);
        $manager->flush();
    }
}
