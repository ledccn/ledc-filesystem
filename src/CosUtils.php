<?php

namespace Ledc\Filesystem;

use DateTimeInterface;
use GuzzleHttp\Psr7\Request as Psr7Request;
use InvalidArgumentException;
use Overtrue\CosClient\Exceptions\InvalidConfigException as CosClientInvalidConfigException;
use Overtrue\CosClient\Signature;
use Overtrue\Flysystem\Cos\CosAdapter;

/**
 * 腾讯云COS工具类
 */
class CosUtils
{
    /**
     * 生成腾讯云COS的预签名URL
     * - 适用于前端直传场景
     * @param CosAdapter $cosAdapter 适配器
     * @param string $method 请求方式
     * @param string $key 资源KEY
     * @param int|string|DateTimeInterface $expires 有效期
     * @return array
     * @throws CosClientInvalidConfigException
     */
    public static function getTemporarySignedUrl(CosAdapter $cosAdapter, string $method, string $key, int|string|DateTimeInterface $expires = '+60 minutes'): array
    {
        if (!in_array($method, ['GET', 'PUT', 'POST'], true)) {
            throw new InvalidArgumentException("Method [$method] not supported.");
        }

        $cos = $cosAdapter->getObjectClient();
        $url = $cos->getObjectUrl($key);
        $config = $cos->getConfig();
        $signature = new Signature($config['secret_id'], $config['secret_key']);
        $request = new Psr7Request($method, $url);
        $authorization = $signature->createAuthorizationHeader($request, $expires);

        return [
            'url' => $url,
            'authorization' => $authorization,
            'parse_url' => parse_url($url),
        ];
    }
}
