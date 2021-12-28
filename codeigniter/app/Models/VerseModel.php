<?php

namespace App\Models;

use CodeIgniter\Model;

class VerseModel extends Model
{
  protected $table = 'verses';
  protected $primaryKey = 'id';
  protected $returnType = \App\Entities\Verse::class;
}
