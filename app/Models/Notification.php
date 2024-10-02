<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    /**
     * October 2, 2024.
     * The notification model is for notifying users of the existence of a document in route.
     * This also allows the users to "receive" the document without having to scan its QR manually
     * or enter its URL in the browser. Notifications will appear in the home.
     * 
     * Fields:
     * id = standard id in RDBMSs
     * created_at = when the notification was 'sent'
     * document_id = document id
     * sender_id = a user model who sent the notification.
     * receiver_id = a user model who will receive the notification.
     * comment = short message attached to notification.
     */

    protected $fillable = ['document_id', 'sender_id', 'receiver_id', 'comment','dismissed_on'];

    public function document() : BelongsTo {
        return $this->belongsTo(Document::class);
    }

    public function sender() : BelongsTo {
        return $this->belongsTo(User::class,'sender_id');
    }

    public function receiver() : BelongsTo {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
