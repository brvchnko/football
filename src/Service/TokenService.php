<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Exceptions\TokenException;
use App\Model\Request\TokenInput;
use App\Repository\UserRepository;
use App\Util\TokenUtil;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class TokenService
{
    private $repository;
    private $encoder;
    private $util;

    public function __construct(UserRepository $repository, UserPasswordEncoderInterface $encoder, TokenUtil $tokenUtil)
    {
        $this->repository = $repository;
        $this->encoder = $encoder;
        $this->util = $tokenUtil;
    }

    public function create(TokenInput $input): string
    {
        $user = $this->repository->findOneByEmailAndStatus($input->getEmail());

        if (!$user instanceof User) {
            throw new TokenException();
        }

        if (!$this->encoder->isPasswordValid($user, $input->getPassword())) {
            throw new TokenException();
        }

        return $this->util->create($user);
    }
}
