<?php

namespace Ledc\Filesystem\Contracts;

/**
 * 获取配置的接口
 */
interface Config
{
    /**
     * @param string $key
     * @return array
     */
    public function get(string $key): array;
}
