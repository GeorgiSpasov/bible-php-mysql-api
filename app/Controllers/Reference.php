<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class Reference extends ResourceController
{
  protected $modelName = 'App\Models\ReferenceModel';
  protected $format = 'json';

  // findVerseRefs
  public function show($verseId = null)
  {
    $references = $this->model->where('verseId', $verseId)->findAll();
    return $this->respond($references);
  }
}
