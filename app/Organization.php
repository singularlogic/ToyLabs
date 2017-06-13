<?php

namespace App;

use App\OrganizationType;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = [
        'name', 'legal_name', 'reg_country', 'reg_number', 'legal_form', 'vat_number', 'address', 'po_box', 'postal_code', 'city',
        'phone', 'fax', 'website_url', 'description', 'owner_id', 'organization_type_id',
    ];
    protected $appends = ['typeSlug'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function organizationType()
    {
        return $this->belongsTo(OrganizationType::class, 'organization_type_id');
    }

    public function getTypeSlugAttribute()
    {
        return $this->organizationType->slug;
    }
}
