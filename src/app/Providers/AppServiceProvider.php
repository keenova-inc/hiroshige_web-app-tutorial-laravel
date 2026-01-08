<?php

namespace App\Providers;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $interfaces = glob(app_path('Repositories') . '/*/*Interface.php');

        foreach ($interfaces as $interface) {
            $filePath = explode('app/', $interface)[1];
            $interfaceFqcn = "App\\" . str_replace(['/', '.php'], ['\\', ''], $filePath);
            $repositoryFqcn = str_replace('Interface', '', $interfaceFqcn);

            $this->app->bind($interfaceFqcn, $repositoryFqcn);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        DB::listen(function (QueryExecuted $query) {
            $binded = $query->toRawSql();
            Log::channel('sql')->info("$binded \n Time: {$query->time}msec");
        });
    }
}
