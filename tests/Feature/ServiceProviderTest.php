<?php

declare(strict_types=1);

namespace Rinvex\Attributes\Tests\Feature;

use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Rinvex\Attributes\Providers\AttributesServiceProvider;

class ServiceProviderTest extends TestCase
{
    /** @return class-string<ServiceProvider> */
    protected function getServiceProviderClass(): string
    {
        return AttributesServiceProvider::class;
    }

    #[Test]
    public function it_is_a_service_provider(): void
    {
        $reflection = new ReflectionClass($this->getServiceProviderClass());

        $this->assertTrue($reflection->isSubclassOf(ServiceProvider::class));
    }

    #[Test]
    public function it_has_provides_method(): void
    {
        $class = $this->getServiceProviderClass();

        $provider = new $class(new Container());

        $this->assertIsArray($provider->provides());
    }
}
