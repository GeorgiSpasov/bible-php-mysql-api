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
    // TODO: Filter duplicates?
    $verses = [];
    $total = NULL;
    $searchTerms = array_filter(explode(' ', $searchTerm));
    $mappedTerms = [];
    if ($isWholeWord == 'true') {
      $mappedTerms = array_map(function ($array_item) {
        return "+$array_item";
      }, $searchTerms);
    } else {
      $mappedTerms = array_map(function ($array_item) {
        return "+*$array_item*";
      }, $searchTerms);
    }

    $allTerms = join(" ", $mappedTerms);
    $total = $this->model->where('language', $language)
      ->whereIn('book', explode(',', $searchBooksString))
      ->where('MATCH (text) AGAINST ("' . $allTerms . '" IN BOOLEAN MODE)')
      ->countAllResults();
    $verses = $this->model->where('language', $language)
      ->whereIn('book', explode(',', $searchBooksString))
      ->where('MATCH (text) AGAINST ("' . $allTerms . '" IN BOOLEAN MODE)')
      ->orderBy('bookNum ASC', 'chapterNum ASC', 'verseNum ASC')
      ->findAll($take, $skip);

    $options = [
      'max-age'  => 604800,
    ];
    $this->response->setCache($options);
    return $this->respond([$verses,  $total]);
  }
}
