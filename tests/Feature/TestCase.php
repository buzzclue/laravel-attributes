<?php

declare(strict_types=1);

namespace Rinvex\Attributes\Tests\Feature;

use Orchestra\Testbench\TestCase as Orchestra;
use Rinvex\Attributes\Models\Attribute;
use Rinvex\Attributes\Models\Type\Boolean;
use Rinvex\Attributes\Models\Type\Datetime;
use Rinvex\Attributes\Models\Type\Integer;
use Rinvex\Attributes\Models\Type\Text;
use Rinvex\Attributes\Models\Type\Varchar;
use Rinvex\Attributes\Providers\AttributesServiceProvider;
use Rinvex\Attributes\Tests\Stubs\User;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate', ['--database' => 'testing']);
        $this->loadLaravelMigrations('testing');

        // Registering the core type map
        Attribute::typeMap([
            'text' => Text::class,
            'bool' => Boolean::class,
            'integer' => Integer::class,
            'varchar' => Varchar::class,
            'datetime' => Datetime::class,
        ]);

        // Push your entity fully qualified namespace
        app('rinvex.attributes.entities')->push(User::class);
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    /**
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return array_values(array_filter([
            AttributesServiceProvider::class,
            // spatie/laravel-sluggable >= 5 registers its config via a provider
            class_exists(\Spatie\Sluggable\SluggableServiceProvider::class)
                ? \Spatie\Sluggable\SluggableServiceProvider::class
                : null,
        ]));
    }
}
