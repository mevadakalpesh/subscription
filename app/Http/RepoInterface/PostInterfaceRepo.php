<?php
namespace App\Http\RepoInterface;
interface PostInterfaceRepo{
  public function getPosts(array $where =[]);
}
