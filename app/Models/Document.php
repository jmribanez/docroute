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
    protected $fillable = ['id','title','description','user_id','document_type','external_party'];

    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function attachments() : HasMany {
        return $this->hasMany(Attachment::class);
    }

    public function routes() : HasMany {
        return $this->hasMany(DocumentRoute::class);
    }
    /**
     * NOTE: September 11, 2024
     * Added document_type and external party.
     * Document type is a string for incoming, outgoing, or internal.
     * External party is for the name of the receiving person if outgoing or sending person if incoming.
     * Remember: incoming is for documents from the outside coming in. Outgoing is for documents from inside going out.
     */
}
