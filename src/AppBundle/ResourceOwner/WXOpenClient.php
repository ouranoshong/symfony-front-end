<?php
/**
 * Created by PhpStorm.
 * User: hong
 * Date: 12/26/16
 * Time: 4:42 PM
 */

namespace AppBundle\ResourceOwner;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Router;


/**
 * Class WXOpenClient
 *
 * @package AppBundle\ResourceOwner
 */
class WXOpenClient
{

    /**
     * @var string
     */
    public $appId = '';
    /**
     * @var string
     */
    public $appSecret = '';
    /**
     * @var string
     */
    public $callbackUri = '';

    private $callbackRoute;

    private $generator;


    public function __construct(array $configs) {

        $this->appId = $configs['app_id'];
        $this->appSecret = $configs['app_secret'];
        $this->callbackRoute = $configs['redirect_route'];
    }

//    public function setRouter(Router $router) {
//          $this->router
//    }

    public function setCallbackUri(Router $router) {

        $this->callbackUri = $router->generate(
            $this->callbackRoute,
            [],
            UrlGeneratorInterface::ABSOLUTE_URL

        );

        return $this;
    }

    /**
     * @param $state
     *
     * @return string
     */
    protected function generateStateFileName($state) {
        return sys_get_temp_dir() . '/wx-server-'.$state;
    }

    /**
     * @return null|string
     */
    public function generateState() {
        $state = md5(date('Y-m-d H:i:s').'wx-server');

        $file = $this->generateStateFileName($state);

        file_put_contents($file, $state);

        if (is_file($file)) {
            return $state;
        }

        return null;
    }

    /**
     * @param $state
     *
     * @return bool
     */
    public function checkState($state) {

        $file = $this->generateState($state);

        if (is_file($file)) {
            return true;
        }

        return false;
    }

    /**
     * @param null $state
     *
     * @return string
     */
    public function generateAuthorizeUrl($state = null) {

        $callback = '';

        if ($this->callbackUri) {
            $callback = urlencode($this->callbackUri);
        }

        if ($state === null) {
            $state = md5(date('Y-m-d: H:i:s'));
        }

        return "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->appId}&redirect_uri={$callback}&response_type=code&scope=snsapi_userinfo&state={$state}#wechat_redirect";
    }


    /**
     * @param $code
     *
     * @return string
     */
    public function generateAccessTokenUrl($code) {
        return "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->appId}&secret={$this->appSecret}&code={$code}&grant_type=authorization_code";
    }


    /**
     * @param $code
     *
     * @return mixed
     */
    public function fetchAccessToken($code) {
        return $this->request($this->generateAccessTokenUrl($code));
    }

    /**
     * @param $openId
     * @param $accessToken
     *
     * @return string
     */
    public function generateUserInfoUrl($openId, $accessToken) {
        return "https://api.weixin.qq.com/sns/userinfo?access_token={$accessToken}&openid={$openId}&lang=zh_CN";
    }

    /**
     * @param $openId
     * @param $accessToken
     *
     * @return mixed
     */
    public function fetchUserInfo($openId, $accessToken) {
        return $this->request($this->generateUserInfoUrl($openId, $accessToken));
    }

    /**
     * @param $url
     *
     * @return mixed
     */
    public function request($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

}
