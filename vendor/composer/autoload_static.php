<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7c4ee13aa652088e551f9252a9ab5be1
{
    public static $prefixLengthsPsr4 = array (
        'B' => 
        array (
            'Buki\\' => 5,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Buki\\' => 
        array (
            0 => __DIR__ . '/..' . '/izniburak/pdox/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit7c4ee13aa652088e551f9252a9ab5be1::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7c4ee13aa652088e551f9252a9ab5be1::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
