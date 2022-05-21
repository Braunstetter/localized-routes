<?php

use Braunstetter\LocalizedRoutes\EventSubscriber\LocaleRewriteSubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

class UnitTest extends TestCase
{

    /**
     * @throws ReflectionException
     */
    public function test_is_locale_supported_works_correctly()
    {
        $routerMock = $this->getMockBuilder(RouterInterface::class)->getMock();
        $routerMock->method('getRouteCollection')->willReturn(new RouteCollection());
        $subscriber = new LocaleRewriteSubscriber($routerMock,  ['de', 'en']);

        $this->assertFalse($this->invokeMethod($subscriber, 'isLocaleSupported', [null]));
    }

    public function test_get_subscribed_events_is_correct()
    {
        $this->assertTrue(LocaleRewriteSubscriber::getSubscribedEvents()[KernelEvents::REQUEST] === ['onKernelRequest', 64]);
    }

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object Instantiated object that we will run method on.
     * @param string $methodName Method names to call
     * @param array $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     * @throws ReflectionException
     */
    public function invokeMethod(object $object, string $methodName, array $parameters = array()): mixed
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}