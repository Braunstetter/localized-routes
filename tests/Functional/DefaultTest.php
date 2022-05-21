<?php
namespace Braunstetter\LocalizedRoutes\Tests\Functional;

use Braunstetter\LocalizedRoutes\Tests\Functional\app\src\TestKernel;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\AcceptHeader;

class DefaultTest extends TestCase
{
    protected Testkernel $kernel;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = new TestKernel();
        $kernel->boot();

        $this->kernel = $kernel;
    }

    public function test_route_gets_redirected()
    {
        $client = new KernelBrowser($this->kernel);
        $client->request('GET', '/test');
        $client->followRedirect();
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertSame('http://localhost/en/test', $client->getRequest()->getUri());
    }

    public function test_route_gets_not_redirected_when_locale_is_not_supported()
    {
        $kernel = new TestKernel(['/Resources/config/framework-unsupported-locale.yaml']);
        $kernel->reboot($kernel->getCacheDir() . '/warmup/unsupported');

        $client = new KernelBrowser($kernel);
        $client->followRedirects();
        $client->request('GET', '/test-unsupported');
        $this->assertSame('http://localhost/test-unsupported', $client->getRequest()->getUri());

        $this->assertFalse($client->getResponse()->isSuccessful());

    }

    public function test_route_gets_redirected_if_locale_is_not_supported_but_default_locale_is()
    {
        $kernel = new TestKernel(['/Resources/config/framework-unsupported-locale-with-default.yaml']);
        $kernel->reboot($kernel->getCacheDir() . '/warmup/unsupported_with_default');

        $client = new KernelBrowser($kernel);
        $client->followRedirects();
        $client->request('GET', '/test-unsupported');
        $this->assertSame('http://localhost/de/test-unsupported', $client->getRequest()->getUri());

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function test_home_fallback_works() {
        $client = new KernelBrowser($this->kernel);
        $client->request('GET', '/');
        $client->followRedirects(false);
        $this->assertTrue($client->getResponse()->isSuccessful());
    }
}