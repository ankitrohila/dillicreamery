<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CorporateGiftingInquiry extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'company_name', 'occasion', 'quantity', 'budget', 'message', 'status'];
    protected $casts = ['budget' => 'decimal:2'];
}
