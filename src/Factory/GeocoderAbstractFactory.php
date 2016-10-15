<?php
/**
 * Geocoder (http://geocoder-php.org)
 *
 * @see       https://github.com/geocoder-php/GeocoderModule for the canonical source repository
 * @copyright Copyright (c) 2016, Julien Guittard <julien.guittard@me.com>
 * @license   https://github.com/geocoder-php/GeocoderModule/blob/master/LICENSE.md New BSD License
 */

namespace ZF\Geocoder\Factory;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Ivory\HttpAdapter\PsrHttpAdapterInterface;
use Zend\Filter\Word\CamelCaseToUnderscore;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class GeocoderAbstractFactory
 *
 * @package     ZF\Geocoder\Factory
 * @version     1.0
 * @author      Julien Guittard <julien.guittard@me.com>
 * @license     https://github.com/geocoder-php/GeocoderModule/blob/master/LICENSE.md New BSD License
 * @link        http://github.com/geocoder-php/GeocoderModule for the canonical source repository
 */
final class GeocoderAbstractFactory implements AbstractFactoryInterface
{
    /**
     * Top-level configuration key indicating fixtures configuration
     *
     * @var string
     */
    const CONFIG_KEY = 'geocoder';

    /**
     * Service manager factory prefix
     *
     * @var string
     */
    const SERVICE_PREFIX = 'Geocoder\\';

    /**
     * Fixtures configuration
     *
     * @var array
     */
    protected $config;

    /**
     * Can we create a geocoder instance by the requested name? (v2)
     *
     * @param ServiceLocatorInterface $container
     * @param string $name Normalized name by which service was requested;
     *     ignored.
     * @param string $requestedName Name by which service was requested, must
     *     start with ZF\Geocoder\
     * @return bool
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $container, $name, $requestedName)
    {
        return $this->canCreate($container, $requestedName);
    }

    /**
     * Can we create a navigation by the requested name? (v2)
     *
     * @param ServiceLocatorInterface $container
     * @param string $name Normalized name by which service was requested;
     *     ignored.
     * @param string $requestedName Name by which service was requested, must
     *     start with Zend\Navigation\
     * @return \Geocoder\Provider\AbstractProvider
     */
    public function createServiceWithName(ServiceLocatorInterface $container, $name, $requestedName)
    {
        return $this($container, $requestedName);
    }

    /**
     * Can the factory create an instance for the service?
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @return bool
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        if (0 !== strpos($requestedName, self::SERVICE_PREFIX)) {
            return false;
        }
        $config = $this->getConfig($container);

        return $this->hasNamedConfig($requestedName, $config['providers']);
    }

    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config  = $this->getConfig($container);
        $providerConfig = $this->getNamedConfig($requestedName, $config['providers']);

        $providerClassName = sprintf('Geocoder\Provider\%s', substr($requestedName, strlen(self::SERVICE_PREFIX)));

        if (is_array($providerConfig)) {
            $httpAdapterServiceName = isset($providerConfig['httpAdapter']) ?
                $providerConfig['httpAdapter'] :
                $config['httpAdapter'];
            if (!$container->has($httpAdapterServiceName)) {
                throw new ServiceNotCreatedException(sprintf(
                    'Could not create %s service because HTTP adapter %s service could not be found',
                    $requestedName,
                    $httpAdapterServiceName
                ));
            }
            $httpAdapter = $container->get($httpAdapterServiceName);
            if (!$httpAdapter instanceof PsrHttpAdapterInterface) {
                throw new ServiceNotCreatedException('HTTP Adapter must be PSR-7 compliant');
            }
            array_unshift($providerConfig, $httpAdapter);

            $reflection = new \ReflectionClass($providerClassName);
            $instance = $reflection->newInstanceArgs(array_values($providerConfig));
        } else {
            $instance = new $providerClassName;
        }
        return $instance;
    }

    /**
     * Get data fixture configuration, if any
     *
     * @param  ContainerInterface $container
     * @return array
     */
    protected function getConfig(ContainerInterface $container)
    {
        if ($this->config !== null) {
            return $this->config;
        }

        if (!$container->has('Config')) {
            $this->config = [];
            return $this->config;
        }

        $config = $container->get('Config');
        if (!isset($config[self::CONFIG_KEY])
            || !is_array($config[self::CONFIG_KEY])
        ) {
            $this->config = [];
            return $this->config;
        }

        $this->config = $config[self::CONFIG_KEY];
        return $this->config;
    }

    /**
     * Extract config name from service name
     *
     * @param string $name
     * @return string
     */
    private function getConfigName($name)
    {
        return $this->toUnderscore(substr($name, strlen(self::SERVICE_PREFIX)));
    }

    /**
     * Does the configuration have a matching named section?
     *
     * @param string $name
     * @param array|\ArrayAccess $config
     * @return bool
     */
    private function hasNamedConfig($name, $config)
    {
        //return (array_search($this->toUnderscore($parts[1]), array_keys($config['providers'])) !== false);
        $withoutPrefix = $this->getConfigName($name);

        if (isset($config[$withoutPrefix])) {
            return true;
        }

        if (isset($config[strtolower($withoutPrefix)])) {
            return true;
        }

        return false;
    }

    /**
     * Get the matching named configuration section.
     *
     * @param string $name
     * @param array|\ArrayAccess $config
     * @return array
     */
    private function getNamedConfig($name, $config)
    {
        $withoutPrefix = $this->getConfigName($name);

        if (isset($config[$withoutPrefix])) {
            return $config[$withoutPrefix];
        }

        if (isset($config[strtolower($withoutPrefix)])) {
            return $config[strtolower($withoutPrefix)];
        }

        return [];
    }

    /**
     * @param $requestedName
     * @return string
     */
    private function toUnderscore($requestedName)
    {
        $filter = new CamelCaseToUnderscore();
        return strtolower($filter->filter($requestedName));
    }
}
