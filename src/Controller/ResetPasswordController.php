<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Exceptions\EcommerceErrorException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class ResetPasswordController extends AbstractController
{
    public function __construct(
        private Security $security,
        private UserPasswordHasherInterface $passwordHasher,
    )
    {
        
    }
    public function __invoke(User $userSent, mixed $data)
    {
        try {
                /** @var ?User $currentUser */
            $currentUser = $this->security->getUser();

            if (!$currentUser) {
                throw new UnauthorizedHttpException('not authorized');
            }

            if (!$userSent->getPlainPassword()) {
                return new EcommerceErrorException("veuillez envoyer un nouveau mot de passe valide");
            }

            if (!$this->passwordHasher->isPasswordValid($currentUser, $userSent->getPassword())) {
                throw new EcommerceErrorException("Le mot de passe actuel n'est pas valide");
            }

            $currentUser->setPlainPassword($userSent->getPlainPassword());
            $currentUser->setPassword($userSent->getPlainPassword());
            return $currentUser;

        } catch(EcommerceErrorException $e) {
            return new JsonResponse(['violations' => [['propertyPath' => 'current-password-false', 'message' => $e->getMessage()]]], 500);
        }
    }
}