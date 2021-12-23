<?php

namespace App\Models;

use CodeIgniter\Model;

class VerseModel extends Model
{
  protected $table = 'verses';
  protected $primaryKey = 'id';
  protected $allowedFields = ['id', 'book', 'chapterNum', 'verseNum', 'text', 'language', 'hasRefs'];
}
