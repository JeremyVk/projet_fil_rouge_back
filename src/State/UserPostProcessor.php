<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Put;
use ApiPlatform\State\ProcessorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserPostProcessor implements ProcessorInterface
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher, private EntityManagerInterface $em)
    {
        
    }
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        dd($data);
        if ($operation instanceof Put) {
            // $hashedPassword = $this->passwordHasher->hashPassword($data, $data->getPlainPassword());
            // dd($data, $hashedPassword);
            dd($this->passwordHasher->isPasswordValid($data, $data->getPlainPassword()));
        }
        if (!$data->getPlainPassword()) {
            return $this->em->flush($data);
        }

        if ($data->getPlainPassword()) {
            $hashedPassword = $this->passwordHasher->hashPassword($data, $data->getPlainPassword());
            $data->setPassword($hashedPassword);
            $this->em->persist($data);
            return $this->em->flush();
        }

        $hashedPassword = $this->passwordHasher->hashPassword(
            $data,
            $data->getPlainPassword()
        );
    }
}
