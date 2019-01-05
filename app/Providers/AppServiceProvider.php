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
        if ($_SERVER['DOCUMENT_ROOT']) {
            $documentRoot = $_SERVER['DOCUMENT_ROOT'];
            DB::listen(function ($query) use($documentRoot){
                // $query->sql // $query->bindings // $query->time
                $file = $documentRoot . '/../storage/logs/sql.php';
                file_put_contents($file,'<?php' . PHP_EOL);

                $handle = fopen($file,'a+');
                $bindingsAndSql  = PHP_EOL . var_export($query->bindings,true).';';
                $bindingsAndSql .= PHP_EOL . '$sql = '."'$query->sql';";
                $bindingsAndSql .= PHP_EOL . '$time = ' . '\''. $query->time . 'ms' . '\';';
                fwrite($handle,$bindingsAndSql);
                fclose($handle);
            });
        }
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
