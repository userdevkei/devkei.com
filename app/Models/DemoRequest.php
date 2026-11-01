<?php

namespace App\Models;

use App\Models\Concerns\HasCommonId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DemoRequest extends Model
{
    use SoftDeletes, HasCommonId;
    protected $keyType = 'string';
    protected $fillable = ['id', 'product_id','name','email','phone','organization',
        'organization_type','preferred_date','preferred_time','message','status','ip_address'];

    protected $casts = ['preferred_date' => 'date'];

    public function product() { return $this->belongsTo(Product::class); }
}
