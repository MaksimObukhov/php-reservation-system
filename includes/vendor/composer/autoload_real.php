<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitc76a976468b970a6d9ab7c56d4c3e899
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInitc76a976468b970a6d9ab7c56d4c3e899', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitc76a976468b970a6d9ab7c56d4c3e899', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitc76a976468b970a6d9ab7c56d4c3e899::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
