<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Verse extends Entity
{
  protected $casts = [
    'bookNum' => 'integer',
    'chapterNum' => 'integer',
    'verseNum' => 'integer',
    'hasRefs' => 'boolean',
  ];
}
