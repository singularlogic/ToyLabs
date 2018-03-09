<?php

namespace App;

use App\OrganizationType;
use Backpack\CRUD\CrudTrait;
use Hootlex\Friendships\Traits\Friendable;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Organization extends Model
{
    use HasMediaTrait, Friendable, CrudTrait;

    protected $fillable = [
        'name', 'legal_name', 'address', 'po_box', 'postal_code', 'country_id', 'twitter', 'facebook', 'instagram',
        'phone', 'fax', 'website_url', 'description', 'owner_id', 'organization_type_id', 'legal_form', 'city',
        'production_scale', 'payment_in', 'is_verified',
    ];
    protected $appends = ['typeSlug', 'rating1', 'rating2', 'rating3'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'organization_user');
    }

    public function organizationType()
    {
        return $this->belongsTo(OrganizationType::class, 'organization_type_id');
    }

    public function getTypeSlugAttribute()
    {
        return $this->organizationType->slug;
    }

    public function products()
    {
        return $this->morphMany(Product::class, 'owner');
    }

    public function designs()
    {
        return $this->hasManyThrough(Design::class, Product::class, 'owner_id')->where('owner_type', Organization::class)->where('is_public');
    }

    public function prototypes()
    {
        return $this->hasManyThrough(Prototype::class, Product::class, 'owner_id')->where('owner_type', Organization::class);
    }

    public function publicProducts()
    {
        return $this->morphMany(Product::class, 'owner')
            ->where('is_public', true);
    }

    public function publicDesigns()
    {
        return $this->hasManyThrough(Design::class, Product::class, 'owner_id')
            ->where('owner_type', Organization::class)
            ->where('designs.is_public', true);
    }

    public function publicPrototypes()
    {
        return $this->hasManyThrough(Prototype::class, Product::class, 'owner_id')
            ->where('owner_type', Organization::class)
            ->where('prototypes.is_public', true);
    }

    public function facilities()
    {
        return $this->hasMany(Facility::class);
    }

    public function markets()
    {
        return $this->belongsToMany(GeographicalMarket::class, 'geographical_market_organization');
    }

    public function awards()
    {
        return $this->belongsToMany(Award::class, 'award_organization')->withPivot('awarded_at')->orderBy('awarded_at', 'desc');
    }

    public function certifications()
    {
        return $this->belongsToMany(Certification::class, 'certification_organization')->withPivot('certified_at', 'is_verified');
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function competencies()
    {
        return $this->belongsToMany(Competency::class, 'competencies_organizations');
    }

    public function paymentTypes()
    {
        return $this->belongsToMany(PaymentType::class, 'payments_organizations');
    }

    public function expertise()
    {
        return $this->belongsToMany(ToyCategory::class, 'toycategories_organizations', 'organization_id', 'category_id');
    }

    public function getServicesAttribute()
    {
        return [
            'expertise'        => $this->expertise->pluck('id'),
            'competencies'     => $this->competencies->pluck('id'),
            'markets'          => $this->markets->pluck('id'),
            'payment_types'    => $this->paymentTypes->pluck('id'),
            'payment_in'       => $this->payment_in,
            'production_scale' => $this->production_scale,
        ];
    }

    public function activeCollaborations()
    {
        return $this->hasMany(Collaboration::class)->where('status', 'accepted')->with('collaboratable');
    }

    public function archivedCollaborations()
    {
        return $this->hasMany(Collaboration::class)->where('status', 'archived')->with('collaboratable');
    }

    public function pendingRatings()
    {
        return $this->morphMany(CollaborationRating::class, 'collaborator')->where('is_pending', true);
    }

    public function ratings()
    {
        return $this->hasMany(CollaborationRating::class, 'organization_id')->where('is_pending', false);
    }

    public function getRating1Attribute()
    {
        return $this->ratings()->avg('rating_1');
    }

    public function getRating2Attribute()
    {
        return $this->ratings()->avg('rating_2');
    }

    public function getRating3Attribute()
    {
        return $this->ratings()->avg('rating_3');
    }
}
