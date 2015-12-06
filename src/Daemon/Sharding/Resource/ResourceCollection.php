<?php

/**
 * Created by PhpStorm.
 * User: Jiang Yu
 * Date: 2015/12/06
 * Time: 14:05.
 */
namespace Daemon\Sharding\Resource;

/**
 * Class ResourceCollection.
 */
class ResourceCollection implements \Iterator, \Countable
{
    /** @var  \SplObjectStorage */
    protected $collection;

    public function __construct()
    {
        $this->collection = new \SplObjectStorage();
    }

    /**
     * @param ResourceInterface $resource
     */
    public function add(ResourceInterface $resource)
    {
        $this->collection->attach($resource, $resource->getUniqueIdentity());
    }

    /**
     * @return string
     */
    public function getHashValue()
    {
        return md5(json_encode($this->getDigestion()));
    }

    /**
     * @return string[]
     */
    public function getDigestion()
    {
        $ret = [];
        /** @var ResourceInterface $resource */
        foreach ($this->collection as $resource) {
            $ret[] = $resource->getUniqueIdentity();
        }

        return $ret;
    }

    /**
     * Return the current element
     *
     * @link http://php.net/manual/en/iterator.current.php
     * @return ResourceInterface
     * @since 5.0.0
     */
    public function current()
    {
        return $this->collection->current();
    }

    /**
     * Move forward to next element
     *
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        $this->collection->next();
    }

    /**
     * Return the key of the current element
     *
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->collection->key();
    }

    /**
     * Checks if current position is valid
     *
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return $this->collection->valid();
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->collection->rewind();
    }

    /**
     * Count elements of an object
     *
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return $this->collection->count();
    }
}
