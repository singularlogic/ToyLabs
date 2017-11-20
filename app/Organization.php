<?php

namespace App;

use App\OrganizationType;
use Hootlex\Friendships\Traits\Friendable;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Organization extends Model
{
    use HasMediaTrait, Friendable;

    protected $fillable = [
        'name', 'legal_name', 'address', 'po_box', 'postal_code', 'country_id', 'twitter', 'facebook', 'instagram',
        'phone', 'fax', 'website_url', 'description', 'owner_id', 'organization_type_id', 'legal_form', 'city',
        'production_scale', 'payment_in',
    ];
    protected $appends = ['typeSlug'];

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

    public function facilities()
    {
        return $this->hasMany(Facility::class);
    }

    public function markets()
    {
        return $this->belongsToMany(GeographicalMarket::class, 'geographical_market_organization');
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
}
