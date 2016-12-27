<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WXOpenController extends Controller
{
    /**
     * @Route("/login/wxopen", name="login_wxopen")
     */
    public function loginAction()
    {
        $url = $this->get('app.resource_owner.wxopen_client')->generateAuthorizeUrl();

        return new Response("<html><body><script type='text/javascript'>window.location.href='{$url}'</script></body></html>");
    }

    /**
     * @Route("/login/callback/wxopen", name="login_callback_wxopen")
     */
    public function loginCallbackAction(Request $request) {
        var_dump($this->getUser());

        return new Response("<html><body>hello callback!</body></html>");
    }

}
