<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Facades\View;
use App\Models\School;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    //
    \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER');
    \Midtrans\Config::$clientKey = env('MIDTRANS_CLIENT');
    \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {

    // Ambil data School dan logo
    $school = School::find(1);

    // Simpan ke session
    session([
      'school_logo' => $school?->logo ? asset('storage/' . $school->logo) : asset('assets/img/favicon/polijati.ico'),
      'school_name' => $school?->name ?? 'Default School Name',
    ]);

    // Bagikan ke semua tampilan (Blade)
    view()->share([
      'school_logo' => session('school_logo'),
      'school_name' => session('school_name'),
    ]);

    Vite::useStyleTagAttributes(function (?string $src, string $url, ?array $chunk, ?array $manifest) {
      if ($src !== null) {
        return [
          'class' => preg_match("/(resources\/assets\/vendor\/scss\/(rtl\/)?core)-?.*/i", $src) ? 'template-customizer-core-css' : (preg_match("/(resources\/assets\/vendor\/scss\/(rtl\/)?theme)-?.*/i", $src) ? 'template-customizer-theme-css' : '')
        ];
      }
      return [];
    });
  }
}
