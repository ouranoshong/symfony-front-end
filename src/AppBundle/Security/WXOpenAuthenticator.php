<?php
/**
 * Created by PhpStorm.
 * User: hong
 * Date: 12/26/16
 * Time: 5:10 PM
 */

namespace AppBundle\Security;


use AppBundle\Entity\ApiUser;
use AppBundle\ResourceOwner\WXOpenClient;
use AppBundle\Security\Exception\NoAuthCodeAuthenticationException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class WXOpenAuthenticator
 *
 * @package AppBundle\Security
 */
class WXOpenAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var WXOpenClient
     */
    private $client;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    private $router;

    public function __construct(WXOpenClient $client, EntityManager $em, Router $router)
    {
        $this->client = $client;
        $this->em = $em;
        $this->router = $router;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {

    }

    public function getCredentials(Request $request)
    {
        if ($request->getPathInfo() != '/login/callback/wxopen') {
            // don't auth
            return;
        }

        return $this->fetchAccessToken($request);
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {

        $existingUser = $userProvider->loadUserByUsername($credentials->openid);

        if ($existingUser) {
            return $existingUser;
        }

        $userInfo = $this->fetchUserInfo($credentials->openid, $credentials->access_token);

        $user = new ApiUser();

        $user->setUserId(2);
        $user->setUsername($userInfo->nickname);
        $user->setApiKey($userInfo->openid);

        $this->em->persist($user);
        $this->em->flush();

        return $user;

    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new Response($exception->getMessageKey());
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return new RedirectResponse($this->router->generate('homepage'));
    }

    public function supportsRememberMe()
    {
        return true;
    }

    private function fetchAccessToken(Request $request) {

        if (($code = $request->query->get('code'))) {

            if (($tokenMessage = $this->client->fetchAccessToken($code)) && ($tokenMessage = json_decode($tokenMessage))) {

                if (isset($tokenMessage->errorcode) && isset($tokenMessage->errmsg) && $tokenMessage->errorcode && $tokenMessage->errmsg) {
                    throw new AuthenticationException($tokenMessage->errmsg, $tokenMessage->errorcode);
                }

                return $tokenMessage;
            }

        }

        throw new NoAuthCodeAuthenticationException();

    }

    private function fetchUserInfo($openId, $accessToken) {

        $userInfo = json_decode($this->client->fetchUserInfo($openId, $accessToken));

        if (isset($userInfo->errorcode) && isset($userInfo->errmsg) && $userInfo->errorcode && $userInfo->errmsg) {
            throw new AuthenticationException($userInfo->errmsg, $userInfo->errorcode);
        }

        return $userInfo;
    }
}
