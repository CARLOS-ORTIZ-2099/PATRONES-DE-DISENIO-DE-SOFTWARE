<?php

namespace exercise_05\interfaces;


interface DocumentInterface
{
  public function readDocument($path);
  public function writeDocument($path);
  public function deleteDocument($path);
  public function createDocument($path);
  public function uploadLargeDocument($path);
}
