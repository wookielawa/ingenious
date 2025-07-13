<?php

namespace Modules\Invoices\Infrastructure\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Invoices\Domain\Enums\StatusEnum;

class EloquentInvoice extends Model
{
    use HasUuids;

    protected $table = 'invoices';

    protected $fillable = [
        'id',
        'customer_name',
        'customer_email',
        'status',
    ];

    protected $casts = [
        'status' => StatusEnum::class,
    ];

    public function productLines(): HasMany
    {
        return $this->hasMany(EloquentProductLine::class, 'invoice_id', 'id');
    }
}
