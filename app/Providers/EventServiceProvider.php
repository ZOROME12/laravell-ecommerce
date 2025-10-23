<?php

namespace App\Providers;

// Import Models and Observers
use App\Models\Order;
use App\Observers\OrderObserver;
use App\Models\Transaction; // Ensure this namespace is correct
use App\Observers\TransactionObserver;

// Import Laravel's base provider and facade
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event; // <-- REMOVED (Not needed for this registration method)

class EventServiceProvider extends ServiceProvider
{
    /**
     * The model observers for your application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $observers = [
        // We can keep these, but the boot() method provides another way
        Order::class => [OrderObserver::class],
        Transaction::class => [TransactionObserver::class],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        // --- ADD EXPLICIT REGISTRATION ---
        // This explicitly tells Laravel to use the observer for the model's events.
        Order::observe(OrderObserver::class);
        Transaction::observe(TransactionObserver::class);
        // --- END ADDITION ---

        // You might have other event listeners registered here using Event::listen(...)
        // Keep those if they exist.
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents(): bool
    {
        // Ensure this returns false (which is the default)
        // If it returns true, it might interfere if discovery is set up incorrectly elsewhere.
        return false;
    }

     /**
      * Register services.
      */
     public function register(): void
     {
         // Usually empty unless you have specific service container bindings
     }
}
