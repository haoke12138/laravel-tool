<?php

namespace ZHK\Tool\Common;

class WechatClient
{
    const AUTHORIZE_URL = 'https://open.weixin.qq.com/connect/qrconnect?';
    const TOKEN_URL = 'https://api.weixin.qq.com/sns/oauth2/access_token?';

    private $config;
    private static $instance;

    public static function instance()
    {
        if (self::$instance) return self::$instance;
        self::$instance = new static();

        return self::$instance;
    }

    public function config($config)
    {
        $this->config = $config;

        return $this;
    }

    # 二维码页面
    public function qrcode()
    {
        $this->verifyConfig();
        $keys = ['appid', 'redirect_uri', 'response_type', 'scope', 'state'];

        return self::AUTHORIZE_URL . http_build_query(array_parts($this->config, $keys)) . '#wechat_redirect';
    }

    # 扫一扫回调
    public function scanNotify(string $code, \Closure $callback)
    {
        if (!$code) throw new \Exception('code不存在，未授权');

        $this->verifyConfig();
        $token_str = self::TOKEN_URL . http_build_query(array_parts($this->config, ['appid', 'secret', 'grant_type'])) . '&code=' . $code;
        $userinfo = json_decode(file_get_contents($token_str), 1);
        if (isset($userinfo['errcode'])) throw new \Exception('授权失败');

        return (bool)$callback($userinfo);
    }

    private function verifyConfig()
    {
        if (empty($this->config)) throw new \Exception('请设置配置信息');
    }
}