<?php

namespace Ledc\Filesystem;

/**
 * 适配器枚举类
 */
enum AdapterEnums
{
    /**
     * 本地
     */
    case local;
    /**
     * Ftp
     */
    case ftp;
    /**
     * 内存
     */
    case memory;
    /**
     * 腾讯云
     */
    case cos;
    /**
     * 七牛云
     */
    case qiniu;
    /**
     * 阿里云
     */
    case oss;
    /**
     * 亚马逊云
     */
    case aws;

    /**
     * 生成带后缀的驱动标识
     * @param string $suffix
     * @return string
     */
    public function key(string $suffix): string
    {
        if ('' === $suffix) {
            return $this->name;
        }

        return $this->name . '_' . $suffix;
    }

    /**
     * 解析驱动名称
     * @param string $key 带后缀的驱动标识
     * @return string
     */
    public static function parse(string $key): string
    {
        return explode('_', $key)[0] ?: $key;
    }
}
