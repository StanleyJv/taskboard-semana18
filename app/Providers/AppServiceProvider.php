<?php

namespace App\Providers;

use App\Services\CalculadoraComision;
use App\Services\Comisiones\ComisionBilletera;
use App\Services\Comisiones\ComisionTarjeta;
use App\Services\Comisiones\ComisionTransferencia;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(
            CalculadoraComision::class,
            fn () => new CalculadoraComision([
                new ComisionTarjeta(),
                new ComisionTransferencia(),
                new ComisionBilletera(),
            ])
        );
    }

    public function boot(): void
    {
        //
    }
}