<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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

}
