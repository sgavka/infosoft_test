<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    public const TYPE_ENTER = 'enter';
    public const TYPE_CREATE_DEPOSIT = 'create_deposit';
    public const TYPE_ACCRUE = 'accrue';
    public const TYPE_CLOSE_DEPOSIT = 'close_deposit';
}
