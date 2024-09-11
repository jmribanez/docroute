<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalRoute extends Model
{
    use HasFactory;

    protected $fillable = ['document_id','user_id','approval_type','action','acted_on','comment'];
    /*
     *  NOTE: September 11, 2024
     *  This is for discarding.
     */
}
