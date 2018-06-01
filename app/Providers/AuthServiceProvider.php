<?php

namespace App\Providers;

use App\ARModel;
use App\Design;
use App\Organization;
use App\Product;
use App\Prototype;
use App\Thread;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
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
        $this->registerMessagingPolicies();

        Passport::routes();
    }

    public function registerProductPolicies()
    {
        Gate::define('view.product', function ($user, Product $product) {
            // Is owner
            if ($product->owner_type === 'App\\User') {
                if ($product->owner_id === $user->id) {
                    return true;
                }
            }

            // Belongs to the organization that owns this product
            if ($product->owner_type === 'App\\Organization') {
                return true;
                if ($user->organizations()->where('id', $product->owner_id)->count() > 0 or $user->myOrganizations()->where('id', $product->owner_id)->count() > 0) {
                    return true;
                }
            }

            // Allow anyone that is collaborating on a design/prototype to view the product
            foreach ($product->designs as $design) {
                if (Gate::allows('collaborate.design', $design)) {
                    return true;
                }
            }
            foreach ($product->prototypes as $prototype) {
                if (Gate::allows('collaborate.prototype', $prototype)) {
                    return true;
                }
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

        Gate::define('owns.product', function ($user, Product $product) {
            if ($product->owner_type === 'App\\User') {
                // Is owner
                return $product->owner_id === $user->id;
            } elseif ($product->owner_type === 'App\\Organization') {
                // Owns the organization that owns this product
                return $user->myOrganizations->where('id', $product->owner_id)->count() > 0;
            }
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

            if (is_a($model, ARModel::class)) {
                return Gate::allows('edit.product', $model->parent->product);
            }

            return false;
        });
    }

    public function registerOrganizationPolicies()
    {
        Gate::define('organization.owner', function ($user, Organization $organization) {
            return $user->id === $organization->owner_id;
        });
    }

    public function registerMessagingPolicies()
    {
        Gate::define('view.thread', function ($user, Thread $thread) {
            $target = $thread->target;

            // If the thread is about a design/prototype, then allow access to owner organization and to collaborators only
            if ($target) {
                return $user->organizations->where('id', $thread->organization_id)->count() > 0 or $user->myOrganizations->where('id', $thread->organization_id)->count() > 0 or Gate::allows('edit.product', $target->product);
            }

            // Otherwise, only allow access to people that are part of the discussion
            return $thread->participants()->where('user_id', $user->id)->count() > 0;
        });
    }
}
