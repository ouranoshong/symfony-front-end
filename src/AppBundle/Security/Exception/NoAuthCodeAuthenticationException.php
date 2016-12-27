<?php
/**
 * Created by PhpStorm.
 * User: hong
 * Date: 12/27/16
 * Time: 9:54 AM
 */

namespace AppBundle\Security\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;
/**
 * Thrown if the user *should* have an authorization code, but there is none.
 *
 * Usually, this is because the user has denied access to your
 * OAuth application.
 */
class NoAuthCodeAuthenticationException extends AuthenticationException
{
    public function getMessageKey()
    {
        return 'Authentication failed! Did you authorize our app?';
    }
}
