<?php

namespace Ledc\Filesystem;

use Aws\Handler\GuzzleV6\GuzzleHandler;
use Aws\S3\S3Client;
use BadMethodCallException;
use League\Flysystem\Adapter\Ftp;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\Ftp\ConnectivityCheckerThatCanFail;
use League\Flysystem\Ftp\FtpAdapter;
use League\Flysystem\Ftp\FtpConnectionOptions;
use League\Flysystem\Ftp\NoopCommandConnectivityChecker;
use League\Flysystem\InMemory\InMemoryFilesystemAdapter;
use League\Flysystem\Local\LocalFilesystemAdapter;
use Ledc\Container\App;
use Ledc\Container\Manager;
use Ledc\Filesystem\Contracts\Config;
use Overtrue\Flysystem\Cos\CosAdapter;
use Overtrue\Flysystem\Qiniu\QiniuAdapter;
use function config;
use function runtime_path;

/**
 * 文件系统适配器工厂类
 * @mixin FilesystemAdapter
 */
class Factory extends Manager
{
    /**
     * 获取配置信息
     * @var Config|null
     */
    public static ?Config $config = null;

    /**
     * 驱动的命名空间
     * @var string|null
     */
    protected ?string $namespace = __NAMESPACE__ . "\\Adapter\\";

    /**
     * 创建文件系统对象
     * @param string|AdapterEnums|null $name 包含后缀的驱动标识（格式如：local_public）
     * @param array $config Filesystem类的配置
     * @return Filesystem
     */
    public static function create(string|AdapterEnums $name = null, array $config = []): Filesystem
    {
        if ($name instanceof AdapterEnums) {
            $name = $name->name;
        }

        $factory = App::pull(static::class);
        $adapter = $factory->driver($name);
        return new Filesystem($adapter, $config);
    }

    /**
     * 获取适配器
     * @param string|null $name 包含后缀的驱动标识
     * @return FilesystemAdapter
     */
    final public function driver(string $name = null): FilesystemAdapter
    {
        return parent::driver($name);
    }

    /**
     * 获取驱动类型
     * @param string $name 包含后缀的驱动标识
     * @return string
     */
    final protected function resolveType(string $name): string
    {
        return AdapterEnums::parse($name);
    }

    /**
     * 获取驱动配置
     * @param string $name 包含后缀的驱动标识
     * @return mixed
     */
    public function resolveConfig(string $name): array
    {
        if (static::$config === null) {
            return config("flysystem.storage.{$name}", []);
        }

        return static::$config->get($name) ?: [];
    }

    /**
     * 默认驱动
     * @return string
     */
    public function getDefaultDriver(): string
    {
        if (static::$config === null) {
            return config('flysystem.default', 'local');
        }

        return static::$config->getDefaultDriver() ?: '';
    }

    /**
     * 创建适配器：本地存储
     * @param array $config
     * @return LocalFilesystemAdapter
     */
    public function createLocalDriver(array $config): LocalFilesystemAdapter
    {
        return new LocalFilesystemAdapter($config['location'] ?? runtime_path());
    }

    /**
     * 创建适配器：腾讯云 COS
     * @param array $config
     * @return CosAdapter
     */
    public function createCosDriver(array $config): CosAdapter
    {
        return new CosAdapter($config);
    }

    /**
     * 创建适配器：七牛云
     * @param array $config
     * @return QiniuAdapter
     */
    public function createQiniuDriver(array $config): QiniuAdapter
    {
        return new QiniuAdapter($config['accessKey'], $config['secretKey'], $config['bucket'], $config['domain']);
    }

    /**
     * 创建适配器：亚马逊云 S3
     * @param array $config
     * @return AwsS3V3Adapter
     */
    public function createAwsDriver(array $config): AwsS3V3Adapter
    {
        $handler = new GuzzleHandler();
        $options = array_merge($config, ['http_handler' => $handler]);
        $client = new S3Client($options);
        return new AwsS3V3Adapter($client, $options['bucket_name'], '');
    }

    /**
     * 创建适配器：Ftp
     * @param array $config
     * @return FtpAdapter
     */
    public function createFtpDriver(array $config): FtpAdapter
    {
        $options = FtpConnectionOptions::fromArray($config);
        $connectivityChecker = new ConnectivityCheckerThatCanFail(new NoopCommandConnectivityChecker());
        return new FtpAdapter($options, null, $connectivityChecker);
    }

    /**
     * 创建适配器：内存
     * @param array $config
     * @return InMemoryFilesystemAdapter
     */
    public function createMemoryDriver(array $config): InMemoryFilesystemAdapter
    {
        return new InMemoryFilesystemAdapter();
    }

    /**
     * 在静态上下文中调用一个不可访问方法时，__callStatic() 会被调用
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public static function __callStatic(string $name, array $arguments)
    {
        $filesystem = static::create();
        if (is_callable([$filesystem, $name])) {
            return static::create()->{$name}(... $arguments);
        }
        throw new BadMethodCallException('未定义的方法：' . $name);
    }
}
