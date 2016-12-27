<?php
/**
 * Created by PhpStorm.
 * User: hong
 * Date: 12/26/16
 * Time: 5:10 PM
 */

namespace AppBundle\Security;


use AppBundle\ResourceOwner\WXOpenClient;
use AppBundle\Security\Exception\NoAuthCodeAuthenticationException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
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

    public function __construct(WXOpenClient $client, EntityManager $em)
    {
        $this->client = $client;
        $this->em = $em;
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
        $this->client->fetchUserInfo();
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return null;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    public function supportsRememberMe()
    {
        return true;
    }

    private function fetchAccessToken(Request $request) {

        if (($code = $request->query->get('code'))) {

            if (($data = $this->client->fetchAccessToken($code)) && ($data = json_decode($data))) {


            }

        }

        throw new NoAuthCodeAuthenticationException();

    }

    private function fetchUserInfo($openId, $accessToken) {
        $data = json_decode($this->client->fetchUserInfo($openId, $accessToken));
    }
}
