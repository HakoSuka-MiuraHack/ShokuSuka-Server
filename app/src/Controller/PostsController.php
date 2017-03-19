<?php
namespace App\Controller;
use Cake\ORM\TableRegistry;

class PostsController extends AppController{
  /*
  * Posts(投稿)にgetアクセスがあった場合呼ばれる
   *
   *
   *
   */
  public function get(){
   $query = TableRegistry::get("Posts")->find()->join([
          'table' => 'PostsUrls',
          'type' => 'LEFT',
          'conditions' => 'PostsUrls.post_id == Posts.id'
      ])->join([
          'table' => 'Urls',
          'type' => 'LEFT',
          'conditions' => 'PostsUrls.url_id == Urls.image_url'
      ]);//posts, posts_urls, urlsを結合して重複部分を取り除き、配列に整列させて返す（未完）
   }

   public function post() {
       $params = $this->queryVaridate($this->request->getQuery());//クエリパラメータ取得
       $insert = [
           'post_message' => $this->request-getQerty('post_message'),
           'user_id' => $this->request->getQuery('user_id'),
           'restaurant_id' => $this->request->getQuery('restaurant_id')
       ];

       $post = TableRegistry::get("Post");
       $entity = $post->patchEntity($post->newEntity(), $insert);// エンティティを作成してバリデートしたクエリパラメータをセット
       $result = $post->save($entity);

       //User id で該当するユーザーのpost_countをインクリメント


   }

   protected function queryVaridate(array $query){
       //id, post_message, user_id, reataurant_id
       return $query;
   }
}
