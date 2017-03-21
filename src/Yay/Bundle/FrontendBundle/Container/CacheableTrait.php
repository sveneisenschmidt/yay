<?php
namespace Bundle\FrontendBundle\Container;

use \Psr\Cache\CacheItemPoolInterface;

/**
 * Class CacheableTrait
 *
 * @package Bundle\FrontendBundle\Container
 */
trait CacheableTrait
{
    /**
     * @return CacheItemPoolInterface
     */
    public function getCache(): CacheItemPoolInterface
    {
        return $this->get('cache.app');
    }

    /**
     * @param string $key
     * @param callable $callback
     *
     * @return mixed
     */
    public function readThrough(string $key, callable $callback)
    {
        $item = $this->getCache()->getItem($key);

        if (!$item->isHit()) {
            $item->set($data = $callback());
            $this->getCache()->save($item);
            return $data;
        }

        return $item->get();
    }
}
