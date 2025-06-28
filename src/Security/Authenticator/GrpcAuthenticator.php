<?php

/**
 * User: morontt
 * Date: 09.02.2025
 * Time: 13:43
 */

namespace TeleBot\Security\Authenticator;

use LogicException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use TeleBot\Security\User;
use TeleBot\Service\RPC\Auth;

class GrpcAuthenticator extends AbstractLoginFormAuthenticator
{
    private HttpUtils $httpUtils;
    private Auth $authService;

    public function __construct(HttpUtils $httpUtils, Auth $authService)
    {
        $this->httpUtils = $httpUtils;
        $this->authService = $authService;
    }

    public function authenticate(Request $request): Passport
    {
        $password = $request->request->get('_password');
        if (!is_string($password)) {
            throw new BadRequestHttpException('The password must be a string');
        }

        $username = $request->request->get('_username');
        if (!is_string($username)) {
            throw new BadRequestHttpException('The username must be a string');
        }

        $username = trim($username);
        if ($request->hasSession()) {
            $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $username);
        }

        return new Passport(
            new UserBadge($username),
            new CustomCredentials([$this, 'checkPassword'], $password),
            [new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token'))]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return $this->httpUtils->createRedirectResponse($request, $this->determineTargetUrl($request));
    }

    public function checkPassword(mixed $credentials, UserInterface $user): bool
    {
        if (!$user instanceof User) {
            throw new LogicException(sprintf('Class "%s" not supported', get_debug_type($user)));
        }

        $token = $this->authService->getToken($user->getUserIdentifier(), $credentials);
        if ($token) {
            $user->setAccessToken($token);

            return true;
        }

        return false;
    }

    protected function getLoginUrl(Request $request): string
    {
        return '/login';
    }

    private function determineTargetUrl(Request $request): string
    {
        if ($request->hasSession()) {
            $targetUrl = $request->getSession()->get('_security.default.target_path');
            if (\is_string($targetUrl) && (str_starts_with($targetUrl, '/') || str_starts_with($targetUrl, 'http'))) {
                $request->getSession()->remove('_security.default.target_path');

                return $targetUrl;
            }
        }

        return '/';
    }
}
