# 文件系统

## 安装

`composer require ledc/filesystem`

### 腾讯云 COS 适配器

`composer require overtrue/flysystem-cos`

### 七牛云 适配器

`composer require overtrue/flysystem-qiniu`

### 内存适配器

`composer require league/flysystem-memory`

## 配置文件

`/config/flysystem.php`

## 使用

```php
use Ledc\Filesystem\AdapterEnums;
use Ledc\Filesystem\Factory;

// 本地
Factory::create(AdapterEnums::local);

// 腾讯云
Factory::create(AdapterEnums::cos);
```