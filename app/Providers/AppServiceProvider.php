<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

// Helper function to format Philippine phone numbers
if (!function_exists('formatPhilippinePhone')) {
    function formatPhilippinePhone($phone) {
        if (!$phone) return '—';
        
        // Remove all non-numeric characters except +
        $phone = preg_replace('/[^0-9+]/', '', $phone);
        
        // Handle different formats
        if (strlen($phone) === 10 && substr($phone, 0, 1) === '9') {
            // 9XXXXXXXXX format -> +63 9XX XXX XXXX
            return '+63 ' . substr($phone, 0, 3) . ' ' . substr($phone, 3, 3) . ' ' . substr($phone, 6);
        } elseif (strlen($phone) === 11 && substr($phone, 0, 2) === '09') {
            // 09XXXXXXXXX format -> +63 9XX XXX XXXX
            $phone = substr($phone, 1);
            return '+63 ' . substr($phone, 0, 3) . ' ' . substr($phone, 3, 3) . ' ' . substr($phone, 6);
        } elseif (substr($phone, 0, 3) === '+63') {
            // Already in +63 format
            $digits = substr($phone, 3);
            if (strlen($digits) === 10) {
                return '+63 ' . substr($digits, 0, 3) . ' ' . substr($digits, 3, 3) . ' ' . substr($digits, 6);
            }
        } elseif (strlen($phone) === 12 && substr($phone, 0, 2) === '63') {
            // 63XXXXXXXXXX format -> +63 9XX XXX XXXX
            $digits = substr($phone, 2);
            if (strlen($digits) === 10) {
                return '+63 ' . substr($digits, 0, 3) . ' ' . substr($digits, 3, 3) . ' ' . substr($digits, 6);
            }
        }
        
        // Return original if no format matched
        return $phone;
    }
}

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Blade directive for formatting Philippine phone numbers
        Blade::directive('phone', function ($phone) {
            return "<?php echo formatPhilippinePhone($phone); ?>";
        });
    }
}
