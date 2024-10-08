<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DocumentRoute extends Model
{
    use HasFactory;

   //  protected $fillable = ['document_id','office_id','user_id','received_on','action_order','action','acted_on','sender_id','sent_on','comment'];
   protected $fillable = ['document_id','office_id','user_id','routed_on','state','action','acted_on','comment'];

    /**
     * NOTE: Apr 4
     * If a record on this document_id exists, then it's marked final.
     * Documents marked final cannot be edited.
     * For the meantime, even attachments are final.
     * 
     * NOTE: September 11, 2024
     * Changed 'received_on' to 'routed_on'
     * Added 'state' with values [Created, Received, Released]
     * Removed 'action_order'
     * Removed 'acted_on'
     * Removed 'sender_id'
     * Removed 'sent_on'
     * 
     * NOTE: September 17, 2024
     * Returned the acted_on field because when the user acts on a document may be different.
     */

     public function user() : BelongsTo {
        return $this->belongsTo(User::class);
     }

     public function document() : BelongsTo {
        return $this->belongsTo(Document::class);
     }

     public static function getDocumentByUser(string $id) {
      //['document_id','office_id','user_id','routed_on','state','action','acted_on','comment'];
      $records = DB::table('document_routes')
      ->orderBy('routed_on','DESC')
      ->where('user_id', $id)
      ->get()
      ->unique('document_id');
      return $records;
     }

     public static function getFirst5DocumentsByUser(string $id) {
      $records = DB::table('document_routes')
      ->where('user_id', $id)
      ->orderBy('routed_on','DESC')
      ->get()
      ->unique('document_id')
      ->take(5);
      return $records;
     }

     public static function getAllDocuments() {
      $records = DB::table('document_routes')
      ->orderBy('routed_on','DESC')
      ->get()
      ->unique('document_id');
      return $records;
     }

   //   public function sender() : BelongsTo {
   //       return $this->belongsTo(User::class,'sender_id');
   //   }
}
