<?php
namespace App\Http\RepoClass;
use App\Models\Post;
use App\Http\RepoInterface\PostInterfaceRepo;
class PostRepo implements PostInterfaceRepo {
  public $post;
  public function __construct(){
    $this->post = new Post();
  }
  
  public function getPosts($where = []){
    return $this->post->where($where)->get();
  }
  
  
}
