<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentRoute extends Model
{
    use HasFactory;

    protected $fillable = ['document_id','office_id','user_id','received_on','sender_id','sent_on','comment'];

    /**
     * NOTE: Apr 4
     * If a record on this document_id exists, then it's marked final.
     * Documents marked final cannot be edited.
     * For the meantime, even attachments are final.
     */

     public function user() : BelongsTo {
        return $this->belongsTo(User::class);
     }

     public function document() : BelongsTo {
        return $this->belongsTo(Document::class);
     }

     public function sender() : BelongsTo {
         return $this->belongsTo(User::class,'sender_id');
     }
}
