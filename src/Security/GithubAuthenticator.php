<?php

namespace App\Security;

use App\Repository\UserRepository;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class GithubAuthenticator extends OAuth2Authenticator implements AuthenticationEntrypointInterface
{
    public function __construct(
        private ClientRegistry $clientRegistry,
        private EntityManagerInterface $entityManager,
        private JWTTokenManagerInterface $JWTManager,
        private UserPasswordHasherInterface $passwordHasher,
        private UserRepository $userRepository
    )
    {

    }

    public function supports(Request $request): ?bool
    {
        // continue ONLY if the current ROUTE matches the check ROUTE
        return $request->attributes->get('_route') === 'connect_github_check';
    }

    public function authenticate(Request $request): Passport
    {
        $client = $this->clientRegistry->getClient('github');
        $accessToken = $this->fetchAccessToken($client);

        return new SelfValidatingPassport(
            new UserBadge($accessToken->getToken(), function() use ($accessToken, $client) {
    
                /** @var GithubUser $githubUser */
                $githubUser = $client->fetchUserFromToken($accessToken);
                $email = $githubUser->getEmail();
                $existingUser = $this->userRepository->findOneBy(['githubId' => $githubUser->getId()]);

                if ($existingUser) {
                    return $existingUser;
                }

                $user = $this->userRepository->findOneBy(['email' => $email]);

                if (!$user) {
                    $user = new User();
                    $response = $githubUser->toArray();
                    $user->setFirstname($response['login']);
                    $user->setLastname($response['login']);
                    $user->setEmail($githubUser->getEmail());
                    $user->setRoles(['ROLE_USER']);

                    $password = $this->generateRandomPassword();
                    $user->setPassword($this->passwordHasher->hashPassword($user, $password));
                }

                $user->setGithubId($githubUser->getId());
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                
                return $user;
            })
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $jwt = $this->JWTManager->create($token->getUser());

        return new RedirectResponse($_ENV['OAUTH_GITHUB_REDIRECTION'] . $jwt, 302);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }
    
   /**
     * Called when authentication is needed, but it's not sent.
     * This redirects to the 'login'.
     */
    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        return new RedirectResponse(
            '/connect/github', // might be the site, where users choose their oauth provider
            Response::HTTP_TEMPORARY_REDIRECT
        );
    }

    private function generateRandomPassword(): string
    {
        // Génère une chaîne de 10 caractères aléatoires
        $bytes = random_bytes(5);
        $password = base64_encode($bytes);
    
        return $password;
    }
}