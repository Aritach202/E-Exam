<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class LeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'department',
        'email',
        'phone',
        'leave_type',
        'reason',
        'start_date',
        'end_date',
        'submitted_at',
        'status',
    ];

    protected $dates = ['start_date', 'end_date', 'submitted_at'];
    protected $table = 'leave_request';
}
