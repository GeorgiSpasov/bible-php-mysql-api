<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Reference extends Entity
{
  protected $casts = [
    'refChapter' => 'integer',
    'refVerse' => 'integer',
  ];
}
