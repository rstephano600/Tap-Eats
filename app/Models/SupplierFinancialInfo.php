<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierFinancialInfo extends Model
{
    use SoftDeletes;

    protected $table = 'supplier_financial_info';

    protected $fillable = [
        'supplier_id',
        'commission_rate',
        'bank_account_name',
        'bank_account_number',
        'bank_name',
        'bank_branch',
        'mobile_money_number',
        'mobile_money_provider',
        'is_primary',
        'is_active',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'commission_rate' => 'decimal:2',
        'is_primary' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
