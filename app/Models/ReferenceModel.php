<?php

namespace App\Models;

use CodeIgniter\Model;

class ReferenceModel extends Model
{
  protected $table = 'references';
  protected $primaryKey = 'id';
  protected $returnType = \App\Entities\Reference::class;
}
