<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;
    public $incrementing = false;
    protected $fillable = ['id','title','description','user_id'];

    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function attachments() : HasMany {
        return $this->hasMany(Attachment::class);
    }
}
