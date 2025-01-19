<?php

use Ledc\Filesystem\AdapterEnums;

/**
 * 文件系统配置
 */
return [
    'default' => 'local',
    'storage' => [
        // 本地私有
        AdapterEnums::local->name => [
            'location' => runtime_path(),
        ],
        // 本地开放
        AdapterEnums::local->key('public') => [
            'location' => public_path(),
        ],
        AdapterEnums::ftp->name => [
            'host' => 'ftp.example.com',
            'username' => 'username',
            'password' => 'password',
            'url' => '' // 静态文件访问域名
            // 'port' => 21,
            // 'root' => '/path/to/root',
            // 'passive' => true,
            // 'ssl' => true,
            // 'timeout' => 30,
            // 'ignorePassiveAddress' => false,
            // 'timestampsOnUnixListingsEnabled' => true,
        ],
        AdapterEnums::memory->name => [
        ],
        AdapterEnums::aws->name => [
            'credentials' => [
                'key' => 'S3_KEY',
                'secret' => 'S3_SECRET',
            ],
            'region' => 'S3_REGION',
            'version' => 'latest',
            'bucket_endpoint' => false,
            'use_path_style_endpoint' => false,
            'endpoint' => 'S3_ENDPOINT',
            'bucket_name' => 'S3_BUCKET',
            'url' => '' // 静态文件访问域名
        ],
        AdapterEnums::oss->name => [
            'accessId' => 'OSS_ACCESS_ID',
            'accessSecret' => 'OSS_ACCESS_SECRET',
            'bucket' => 'OSS_BUCKET',
            'endpoint' => 'OSS_ENDPOINT',
            'url' => '' // 静态文件访问域名
            // 'timeout' => 3600,
            // 'connectTimeout' => 10,
            // 'isCName' => false,
            // 'token' => null,
            // 'proxy' => null,
        ],
        AdapterEnums::qiniu->name => [
            'accessKey' => 'QINIU_ACCESS_KEY',
            'secretKey' => 'QINIU_SECRET_KEY',
            'bucket' => 'QINIU_BUCKET',
            'domain' => 'QINBIU_DOMAIN',
            'url' => '' // 静态文件访问域名
        ],
        AdapterEnums::cos->name => [
            'region' => 'COS_REGION',
            'app_id' => 'COS_APPID',
            'secret_id' => 'COS_SECRET_ID',
            'secret_key' => 'COS_SECRET_KEY',
            // 可选，如果 bucket 为私有访问请打开此项
            // 'signed_url' => false,
            'bucket' => 'COS_BUCKET',
            'read_from_cdn' => false,
            'url' => '' // 静态文件访问域名
            // 'timeout' => 60,
            // 'connect_timeout' => 60,
            // 'cdn' => '',
            // 'scheme' => 'https',
        ],
    ],
];
