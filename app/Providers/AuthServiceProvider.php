<?php

namespace App\Providers;

use App\Design;
use App\Organization;
use App\Product;
use App\Prototype;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Spatie\MediaLibrary\Media;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $this->registerProductPolicies();
        $this->registerDesignPolicies();
        $this->registerPrototypePolicies();
        $this->registerMediaPolicies();
        $this->registerOrganizationPolicies();
    }

    public function registerProductPolicies()
    {
        Gate::define('view.product', function ($user, Product $product) {
            if ($product->owner_type === 'App\\User') {
                // Is owner
                return $product->owner_id === $user->id;
            } elseif ($product->owner_type === 'App\\Organization') {
                // Belongs to the organization that owns this product
                return $user->organizations->where('id', $product->owner_id)->count() > 0 or $user->myOrganizations->where('id', $product->owner_id)->count() > 0;
            } else {
                // We don't have collaborations on products, thus no more checks
            }

            return false;
        });

        Gate::define('edit.product', function ($user, Product $product) {
            if ($product->owner_type === 'App\\User') {
                // Is owner
                return $product->owner_id === $user->id;
            } elseif ($product->owner_type === 'App\\Organization') {
                // Belongs to the organization that owns this product
                return $user->organizations->where('id', $product->owner_id)->count() > 0 or $user->myOrganizations->where('id', $product->owner_id)->count() > 0;
            }

            return false;
        });
    }

    public function registerDesignPolicies()
    {
        Gate::define('collaborate.design', function ($user, Design $design) {
            foreach ($design->collaborations as $c) {
                if ($user->organizations->where('id', $c->organization_id)->count() > 0) {
                    return true;
                }
            }

            return false;
        });
    }

    public function registerPrototypePolicies()
    {
        Gate::define('collaborate.prototype', function ($user, Prototype $prototype) {
            foreach ($prototype->collaborations as $c) {
                if ($user->organizations->where('id', $c->organization_id)->count() > 0) {
                    return true;
                }
            }

            return false;
        });
    }

    public function registerMediaPolicies()
    {
        Gate::define('edit.media', function ($user, Media $media) {
            $model = $media->model;
            if (is_a($model, Product::class)) {
                return Gate::allows('edit.product', $model);
            }

            if (is_a($model, Design::class) or is_a($model, Prototype::class)) {
                return Gate::allows('edit.product', $model->product);
            }

            return false;
        });
    }

    public function registerOrganizationPolicies()
    {
        Gate::define('edit.organization', function ($user, Organization $organization) {
            return $user->id === $org->owner_id;
        });
    }

}
