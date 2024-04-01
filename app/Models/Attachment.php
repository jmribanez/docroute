<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = ['document_id','orig_filename','url'];
    // Note: URL also contains the filename. Filename must be prepended by document_id.
    // Note: user_id removed from fillable attribute. Why? document already belongs to user.
    // Note: orig_filename added so people can remember their files by name.

    public function document() : BelongsTo {
        return $this->belongsTo(Document::class);
    }
}
