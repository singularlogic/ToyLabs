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
    ];
    protected $appends = ['typeSlug'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'organization_user');
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

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
