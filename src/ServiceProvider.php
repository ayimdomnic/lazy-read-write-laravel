<?php


namespace Ayimdomnic\LazySql;

use Ayimdomnic\LazySql\Logic\Connection;
use Ayimdomnic\LazySql\Logic\Connector;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider  extends BaseServiceProvider
{

    /**
     * Bootstrap the Default Laravel application events
     *
     * @return void
     */
    public function boot()
    {
        Model::setConnectionResolver($this->app['db']);
        Model::setEventDispatcher($this->app['events']);
    }

    public function register()
    {

        // Add database driver.
        $this->app->resolving('db', function ($db) {
            $db->extend('ayim-mysql', function ($config, $name) {
                $config['name'] = $name;
                return new Connection($config);
            });
        });

        // Add connector for queue support.
        $this->app->resolving('queue', function ($queue) {
            $queue->addConnector('ayim-mysql', function () {
                return new Connector($this->app['db']);
            });
        });

    }

}