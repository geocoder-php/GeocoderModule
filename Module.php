<?php

namespace ZF\Geocoder;

/**
 * Class Module
 *
 * @package     ZF\Geocoder
 * @version     1.0
 * @author      Julien Guittard <julien.guittard@mme.com>
 * @license     https://opensource.org/licenses/BSD-3-Clause New BSD License
 * @link        http://github.com/jguittard/geocodermodule for the canonical source repository
 */
class Module
{
    /**
     * Returns configuration to merge with application configuration
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}
