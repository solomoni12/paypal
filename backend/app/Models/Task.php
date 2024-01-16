<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'payment_status', 'transaction_id'];

    public function markAsPaid($transactionId)
    {
        $this->update([
            'payment_status' => 'completed',
            'transaction_id' => $transactionId,
        ]);
    }
}
