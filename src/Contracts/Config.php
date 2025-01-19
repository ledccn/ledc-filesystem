<?php

namespace Ledc\Filesystem\Contracts;

/**
 * 获取配置的接口
 */
interface Config
{
    /**
     * 根据驱动标识获取配置
     * @param string $key 包含后缀的驱动标识
     * @return array
     */
    public function get(string $key): array;

    /**
     * 获取默认的文件系统驱动标识
     * @return string|null 包含后缀的驱动标识
     */
    public function getDefaultDriver(): ?string;
}
