<?php

namespace App\Security;

use App\Repository\ApiTokenRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

class ApiTokenAuthenticator extends AbstractAuthenticator
{
    private ApiTokenRepository $repository;


    public function __construct(ApiTokenRepository $repository)
    {
        $this->repository = $repository;
    }

    public function supports(Request $request): ?bool
    {
        return $request->headers->get('Authorization') && str_starts_with($request->headers->get('Authorization'), 'Bearer');
    }

    public function authenticate(Request $request): Passport
    {
        $tokenString = str_replace('Bearer ', '', $request->headers->get('Authorization'));

        $apiToken = $this->repository->findOneByTokenNotExpires($tokenString, new \DateTime())
            ?? throw new CustomUserMessageAuthenticationException('token is expired or invalid ');

        $user = $apiToken->getUser();
        return new Passport(
            new UserBadge($user->getEmail()),
            new CustomCredentials(
            // If this function returns anything else than `true`, the credentials
            // are marked as invalid.
            // The $credentials parameter is equal to the next argument of this class
                function ($credentials, UserInterface $user) {
                    return $user->getApitokens()->contains($credentials);
                },

                // The custom credentials
                $apiToken
            ));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
//        dd($request, $token, $firewallName);
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse(['message' => $exception->getMessage()], 401);
    }

//    public function start(Request $request, AuthenticationException $authException = null): Response
//    {
//        /*
//         * If you would like this class to control what happens when an anonymous user accesses a
//         * protected page (e.g. redirect to /login), uncomment this method and make this class
//         * implement Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface.
//         *
//         * For more details, see https://symfony.com/doc/current/security/experimental_authenticators.html#configuring-the-authentication-entry-point
//         */
//    }
}
