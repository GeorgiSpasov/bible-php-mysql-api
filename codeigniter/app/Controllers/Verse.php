<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class Verse extends ResourceController
{
  protected $modelName = 'App\Models\VerseModel';
  protected $format = 'json';

  public function show($language = null, $id = null)
  {
    $verses = $this->model->where('id', "$language.$id")->first();
    $options = [
      'max-age'  => 604800,
    ];
    $this->response->setCache($options);
    return $this->respond($verses);
  }

  public function chapter($language, $book, $chapterNum)
  {
    $verses = $this->model->where('language', $language)
      ->where('book', $book)
      ->where('chapterNum', $chapterNum)
      ->orderBy('verseNum', 'asc')
      ->findAll();
    $options = [
      'max-age'  => 604800,
    ];
    $this->response->setCache($options);
    return $this->respond($verses);
  }

  public function range($language, $book, $chapterNum, $fromVerseNum, $toVerseNum)
  {
    $verses = $this->model->where('language', $language)
      ->where('book', $book)
      ->where('chapterNum', $chapterNum)
      ->where('verseNum >=', $fromVerseNum)
      ->where('verseNum <=', $toVerseNum)
      ->orderBy('verseNum', 'asc')
      ->findAll();
    $options = [
      'max-age'  => 604800,
    ];
    $this->response->setCache($options);
    return $this->respond($verses);
  }

  public function search($language, $isWholeWord, $searchTerm, $searchBooksString, $take, $skip)
  {
    // limit is $take, offset - $skip
    $verses = [];
    $total = '';
    if ($isWholeWord == 'true') {
      if ($skip == 0) {
        $total = $this->model->where('language', $language)
          ->whereIn('book', explode(',', $searchBooksString))
          ->where('MATCH (text) AGAINST ("' . $searchTerm . '")', NULL, FALSE)
          ->countAllResults();
      }
      $verses = $this->model->where('language', $language)
        ->whereIn('book', explode(',', $searchBooksString))
        ->where('MATCH (text) AGAINST ("' . $searchTerm . '")', NULL, FALSE)
        // ->orderBy('book ASC', 'chapterNum ASC', 'verseNum ASC')
        ->findAll($take, $skip);
    } else {
      if ($skip == 0) {
        $total = $this->model->where('language', $language)
          ->whereIn('book', explode(',', $searchBooksString))
          ->like('text', $searchTerm)
          ->countAllResults();
      }
      $verses = $this->model->where('language', $language)
        ->whereIn('book', explode(',', $searchBooksString))
        ->like('text', $searchTerm)
        // ->orderBy('book ASC', 'chapterNum ASC', 'verseNum ASC')
        ->findAll($take, $skip);
    }

    $options = [
      'max-age'  => 604800,
    ];
    $this->response->setCache($options);
    return $this->respond([$verses,  $total]);
  }
}
