<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Utilisateur;

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
        // Define authorization gates based on permissions
        Gate::define('moderer_contenu', function (Utilisateur $user) {
            return $user->hasPermission('moderer_contenu');
        });

        Gate::define('gerer_utilisateurs', function (Utilisateur $user) {
            return $user->hasPermission('gerer_utilisateurs');
        });

        Gate::define('gerer_ressources', function (Utilisateur $user) {
            return $user->hasPermission('gerer_ressources');
        });

        Gate::define('gerer_activites', function (Utilisateur $user) {
            return $user->hasPermission('gerer_activites');
        });

        Gate::define('gerer_commentaires', function (Utilisateur $user) {
            return $user->hasPermission('gerer_commentaires');
        });

        Gate::define('voir_statistiques', function (Utilisateur $user) {
            return $user->hasPermission('voir_statistiques');
        });
    }
}
