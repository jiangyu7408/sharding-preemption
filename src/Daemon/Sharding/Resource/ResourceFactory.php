<?php

/**
 * Created by PhpStorm.
 * User: Jiang Yu
 * Date: 2015/12/06
 * Time: 16:17.
 */
namespace Daemon\Sharding\Resource;

/**
 * Class ResourceFactory.
 */
class ResourceFactory
{
    /**
     * @var ResourceInterface
     */
    protected $prototype;

    /**
     * @param ResourceInterface $prototype
     */
    public function setPrototype(ResourceInterface $prototype)
    {
        $this->prototype = $prototype;
    }

    /**
     * @param array $setting
     *
     * @return ResourceInterface
     */
    public function make(array $setting)
    {
        if ($this->prototype === null) {
            throw new \BadMethodCallException('set resource prototype first');
        }
        $resource = clone $this->prototype;
        $keys = array_keys(get_object_vars($resource));
        assert(count($keys) > 0, 'Resource properties');
        foreach ($keys as $key) {
            if (!array_key_exists($key, $setting)) {
                throw new \InvalidArgumentException('key not found: '.$key);
            }
            $resource->{$key} = $setting[$key];
        }

        return $resource;
    }
}
