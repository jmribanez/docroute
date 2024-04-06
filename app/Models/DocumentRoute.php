<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentRoute extends Model
{
    use HasFactory;

    protected $fillable = ['document_id','office_id','user_id','received_on','comment'];

    /**
     * NOTE: Apr 4
     * If a record on this document_id exists, then it's marked final.
     * Documents marked final cannot be edited.
     * For the meantime, even attachments are final.
     */
}
