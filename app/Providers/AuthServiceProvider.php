<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gates;
use App\Gates\TeamUserGate;
use App\Models\TeamInvitation;
use App\Models\User;
use App\Policies\TeamInvitationPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use ReflectionClass;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        TeamInvitation::class => TeamInvitationPolicy::class,
    ];

    protected array $gates = [
        TeamUserGate::class,
    ];

    public function gates(): array
    {
        return $this->gates;
    }

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->autoDefineGates();
    }

    public function autoDefineGates(): void
    {
        // Loop through all the gates from the gates array
        foreach ($this->gates() as $gateClass) {
            // To make the names of the gates unique we will use the short name of the class with the method name.
            $shortName = (new ReflectionClass($gateClass))->getShortName();

            // Get gate class methods.
            $gateMethods = get_class_methods($gateClass);

            foreach ($gateMethods as $gateMethod) {
                // Define the new gates with the short name and the method name, passing the Auth user and the rest of the arguments.
                Gate::define($shortName.'.'.$gateMethod, function (User $user, ...$args) use ($gateClass, $gateMethod) {
                    return $gateClass::$gateMethod($user, ...$args);
                });
            }
        }
    }
}
