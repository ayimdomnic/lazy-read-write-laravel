<?php


namespace Ayimdomnic\LazySql;

class Config
{
    /**
     * @param array $config
     * @return bool
     */
    public static function hasReadConfig(array $config)
    {
        return isset($config['read']);
    }

    /**
     * @param array $config
     * @return bool
     */
    public static function hasWriteConfig(array $config)
    {
        return isset($config['write']);
    }

    /**
     * @param array $config
     * @return array
     */
    public static function getReadConfig(array $config)
    {
        $readConfig = self::getReadWriteConfig($config, 'read');
        if (isset($readConfig['host']) && is_array($readConfig['host'])) {
            $readConfig['host'] = count($readConfig['host']) > 1
                ? $readConfig['host'][array_rand($readConfig['host'])]
                : $readConfig['host'][0];
        }
        return self::mergeReadWithWriteConfig($config, $readConfig);
    }

    /**
     * @param array $config
     * @return array
     */
    public static function getWriteConfig(array $config)
    {
        $writeConfig = self::getReadWriteConfig($config, 'write');
        return self::mergeReadWithWriteConfig($config, $writeConfig);
    }

    /**
     * @param array $config
     * @param string $type
     * @return mixed
     */
    private static function getReadWriteConfig(array $config, string $type)
    {
        if (isset($config[$type[0]])) {
            return $config[$type[array_rand($config[$type])]];
        }
        return $config[$type];
    }

    /**
     * @param array $config
     * @param $merge
     * @return array
     */
    private static function mergeReadWithWriteConfig(array $config, $merge)
    {
        return Arr::except(array_merge($config, $merge), ['read', 'write']);
    }

    /**
     * @param array $config
     * @param $name
     * @return array
     */
    public static function parseConfig(array $config, $name)
    {
        return Arr::add(Arr::add($config, 'prefix', ''), 'name', $name);
    }
}
