<?php

namespace ZF\Geocoder\Factory;

use Ivory\HttpAdapter\PsrHttpAdapterInterface;
use Zend\Filter\Word\CamelCaseToUnderscore;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class GeocoderAbstractFactory
 *
 * @package     ZF\Geocoder\Factory
 * @version     1.0
 * @author      Julien Guittard <julien.guittard@mme.com>
 * @license     https://opensource.org/licenses/BSD-3-Clause New BSD License
 * @link        http://github.com/jguittard/geocodermodule for the canonical source repository
 */
class GeocoderAbstractFactory implements AbstractFactoryInterface
{
    /**
     * Determine if we can create a service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return bool
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        if (!$serviceLocator->has('Config')) {
            return false;
        }

        $config = $serviceLocator->get('Config');

        if (!isset($config['geocoder']) || !isset($config['geocoder']['providers'])) {
            return false;
        }

        $config = $config['geocoder'];

        $parts = explode('\\', $requestedName);

        if (count($parts) !== 2 || $parts[0] !== 'Geocoder') {
            return false;
        }

        return (array_search($this->toUnderscore($requestedName), array_keys($config['providers'])) !== false);
    }

    /**
     * Create service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return mixed
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $config = $serviceLocator->get('Config');
        $config = $config['geocoder'];

        $parts = explode('\\', $requestedName);

        $providerName = $parts[1];

        $providerClassName = '\\Geocoder\\Provider\\' . $providerName;

        $providerConfig = $config['providers'][$this->toUnderscore($providerName)];

        if (is_array($providerConfig)) {
            $httpAdapterServiceName = isset($providerConfig['httpAdapter']) ?
                $providerConfig['httpAdapter'] :
                $config['httpAdapter'];
            if (!$serviceLocator->has($httpAdapterServiceName)) {
                throw new ServiceNotCreatedException(sprintf(
                    'Could not create %s service because HTTP adapter %s service could not be found',
                    $requestedName,
                    $httpAdapterServiceName
                ));
            }
            $httpAdapter = $serviceLocator->get($httpAdapterServiceName);
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
     * @param $requestedName
     * @return string
     */
    protected function toUnderscore($requestedName)
    {
        $filter = new CamelCaseToUnderscore();
        return strtolower($filter->filter($requestedName));
    }
}
