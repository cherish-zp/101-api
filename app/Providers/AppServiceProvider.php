<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        if ($this->app->environment() == 'local') {
            $this->createSql();
        }

    }

    public function createSql()
    {
        file_put_contents('sql.php','<?php' . PHP_EOL);

        DB::listen(function ($query) {
            // $query->sql
            // $query->bindings
            // $query->time
            $file = "sql.php";
            $handle = fopen($file,'a+');
            $bindingsAndSql  = PHP_EOL . var_export($query->bindings,true).';';
            $bindingsAndSql .= PHP_EOL . '$sql = '."'$query->sql';";
            $bindingsAndSql .= PHP_EOL . '$time = ' . '\''. $query->time . 'ms' . '\';';
            fwrite($handle,$bindingsAndSql);
            fclose($handle);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }
}
