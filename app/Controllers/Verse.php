<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class Verse extends ResourceController
{
  protected $modelName = 'App\Models\VerseModel';
  protected $format = 'json';

  // findVerseById
  public function show($language = null, $id = null)
  {
    $verses = $this->model->where('id', "$language.$id")->first();
    return $this->respond($verses);
    if ($verses) {
      return $this->respond($verses);
    } else {
      return $this->failNotFound('No Data Found with id: ' . "$language.$id");
    }
  }

  // getChapter
  public function chapter($language, $book, $chapterNum)
  {
    $verses = $this->model->where('language', $language)
      ->where('book', $book)
      ->where('chapterNum', $chapterNum)
      ->orderBy('verseNum', 'asc')
      ->findAll();
    return $this->respond($verses);
  }

  // findVersesBetweenNums
  public function range($language, $book, $chapterNum, $fromVerseNum, $toVerseNum)
  {
    $verses = $this->model->where('language', $language)
      ->where('book', $book)
      ->where('chapterNum', $chapterNum)
      ->where('verseNum >=', $fromVerseNum)
      ->where('verseNum <=', $toVerseNum)
      ->orderBy('verseNum', 'asc')
      ->findAll();
    return $this->respond($verses);
  }
}
