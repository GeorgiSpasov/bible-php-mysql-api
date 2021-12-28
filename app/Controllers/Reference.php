<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class Reference extends ResourceController
{
  protected $modelName = 'App\Models\ReferenceModel';
  protected $format = 'json';

  public function show($verseId = null)
  {
    $references = $this->model->where('verseId', $verseId)->findAll();
    $options = [
      'max-age'  => 604800,
    ];
    $this->response->setCache($options);
    return $this->respond($references);
  }
}
