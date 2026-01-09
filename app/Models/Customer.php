<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

/**
 * Dedicated authenticatable model for customers.
 *
 * This model shares the same underlying table as the main User model but
 * enforces the `customer` role via a global scope so that the custom guard can
 * safely authenticate only customer accounts.
 */
class Customer extends User
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Apply the customer role scope automatically.
     */
    protected static function booted(): void
    {
        static::addGlobalScope('customer_role', function (Builder $builder) {
            $builder->where('role', 'customer');
        });

        static::creating(function (Customer $customer) {
            $customer->role = 'customer';
            if (is_null($customer->status)) {
                $customer->status = 1;
            }
        });
    }

    /**
     * Scope active customers.
     */
    public function scopeActive($builder)
    {
        return $builder->where('status', 1);
    }
}

