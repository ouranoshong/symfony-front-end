<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public $token = 'HelloWorld';

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
//        return $this->render('default/index.html.twig', [
//            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
//        ]);

//        $echostr = $request->query->get('echostr');
//
//        if ($this->checkSignature($request)) {
//            return new Response($echostr);
//        }
//
//        return new Response('');


//        $user = json_encode($this->getUser());
//        return new Response($user);

        $user = $this->getUser();
        return $this->render('default/index.html.twig', compact('user'));
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function adminAction()
    {
        return new Response('<html><body>Welcome to Admin page!</body></html>');
    }

    private function checkSignature(Request $request)

    {

        $signature = $request->query->get("signature");

        $timestamp = $request->query->get("timestamp");

        $nonce = $request->query->get("nonce");

        $tmpArr = array($this->token, $timestamp, $nonce);

        sort($tmpArr, SORT_STRING);

        $tmpStr = implode( $tmpArr );

        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;

        }else{
            return false;
        }

    }


}
