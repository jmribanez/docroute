<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Office extends Model
{
    use HasFactory;

    protected $fillable = [
        'office_name',
        'reports_to_office',
        'office_head',
        'office_head_title',
    ];

    public function reportsToOffice() : BelongsTo {
        return $this->belongsTo(Office::class, 'reports_to_office');
    }

    public function officeHead() : HasOne {
        return $this->hasOne(User::class, 'id', 'office_head');
    }

    public function personnel() : HasMany {
        return $this->hasMany(User::class);
    }
}
