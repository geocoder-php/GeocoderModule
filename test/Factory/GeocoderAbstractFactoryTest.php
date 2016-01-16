<?php

namespace ZFTest\Geocoder;

use Ivory\HttpAdapter\CurlHttpAdapter;
use Ivory\HttpAdapter\Zend2HttpAdapter;
use Zend\ServiceManager\ServiceManager;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use ZF\Geocoder\Factory\GeocoderAbstractFactory;

/**
 * Class GeocoderAbstractFactoryTest
 *
 * @package     ZFTest\Geocoder
 * @version     1.0
 * @author      Julien Guittard <julien.guittard@mme.com>
 * @license     https://opensource.org/licenses/BSD-3-Clause New BSD License
 * @link        http://github.com/jguittard/geocodermodule for the canonical source repository
 */
class GeocoderAbstractFactoryTest extends AbstractHttpControllerTestCase
{
    /**
     * @var GeocoderAbstractFactory
     */
    protected $factory;

    /**
     * @var ServiceManager
     */
    protected $services;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     *
     */
    protected function setUp()
    {
        $this->factory = new GeocoderAbstractFactory();
        $this->services = new ServiceManager();

        $this->setApplicationConfig([
            'modules' => [
                'ZF\Geocoder',
            ],
            'module_listener_options' => [
                'module_paths' => [__DIR__ . '/../../'],
                'config_glob_paths' => []
            ],
            'service_listener_options' => [],
            'service_manager' => [],
        ]);
        parent::setUp();
    }

    /**
     * @return array
     */
    public function getServiceNameSet()
    {
        return [
            ['Foo', false],
            ['Foo\Bar', false],
            ['GoogleMaps', false],
            ['Foo\GoogleMaps', false],
            ['Geocoder', false],
            ['Geocoder\GoogleMaps', true],
            ['Geocoder\Geonames', true],
            ['Geocoder\GoogleMaps\Baz', false],
        ];
    }

    /**
     * @param $string
     * @param $isValid
     * @dataProvider getServiceNameSet
     *               GeocoderAbstractFactory::canCreateServiceWithName
     */
    public function testCanCreateServiceWithName($string, $isValid)
    {
        $this->services->setService('Config', include __DIR__ . '/../../config/zf.geocoder.local.php.dist');
        $boolean = $this->factory->canCreateServiceWithName($this->services, 'string', $string);
        $this->assertEquals($isValid, $boolean);
    }

    public function testCreateServiceWithName()
    {
        $this->services->setService('Config', include __DIR__ . '/../TestAsset/providers.php');
        $this->services->setService('Foo\Bar\HttpAdapter', new Zend2HttpAdapter());
        $this->assertInstanceOf(
            'Geocoder\Provider\AbstractProvider',
            $this->factory->createServiceWithName($this->services, 'foo', 'Geocoder\GoogleMaps')
        );
    }

    public function testLocalAdapterOverridesGlobal()
    {
        $this->services->setService('Config', include __DIR__ . '/../TestAsset/single-provider.php');
        $this->services->setService('Foo\Bar\HttpAdapter', new Zend2HttpAdapter());
        $this->services->setService('Foo\Baz\HttpAdapter', new CurlHttpAdapter());

        $instance = $this->factory->createServiceWithName($this->services, 'foo', 'Geocoder\GoogleMaps');
        $this->assertInstanceOf('Ivory\HttpAdapter\CurlHttpAdapter', $instance->getAdapter());
    }
}
