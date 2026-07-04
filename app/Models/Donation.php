<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $fillable = [
        'user_id', 'project_id', 'amount', 'currency', 'transaction_id',
        'bank_tran_id', 'card_type', 'card_no', 'card_issuer', 'card_brand',
        'card_issuer_country', 'currency_amount', 'currency_type', 'tran_date',
        'val_id', 'stripe_session_id', 'payment_method', 'status', 'donor_name', 'donor_email', 'message',
    ];

    protected function casts(): array
    {
        return ['amount' => 'decimal:2'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
