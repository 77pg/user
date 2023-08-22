<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4c24d79bba0c15e7e26782f6771528a5
{
    public static $prefixLengthsPsr4 = array (
        'E' => 
        array (
            'Ecpay\\Sdk\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Ecpay\\Sdk\\' => 
        array (
            0 => __DIR__ . '/..' . '/ecpay/sdk/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit4c24d79bba0c15e7e26782f6771528a5::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit4c24d79bba0c15e7e26782f6771528a5::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit4c24d79bba0c15e7e26782f6771528a5::$classMap;

        }, null, ClassLoader::class);
    }
}
