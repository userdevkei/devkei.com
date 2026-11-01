<?php

namespace App\Models;

use App\Models\Concerns\HasCommonId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuoteRequest extends Model
{
    use HasCommonId, softDeletes;

    protected $keyType = 'string';
    protected $fillable = ['id', 'product_id','name','email','phone','organization',
        'organization_type','user_count','hosting_preference','requirements',
        'budget_range','status','ip_address'];

    public function product() { return $this->belongsTo(Product::class); }
}
