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
  /*public function get(){
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
           'post_message' => $this->request-getQery('post_message'),
           'user_id' => $this->request->getQuery('user_id'),
           'restaurant_id' => $this->request->getQuery('restaurant_id')
       ];

       $post = TableRegistry::get("Post");
       $entity = $post->patchEntity($post->newEntity(), $insert);// エンティティを作成してバリデートしたクエリパラメータをセット
       $result = $post->save($entity);

       //User id で該当するユーザーのpost_countをインクリメント
   }*/

   public function postActivity(){
       mb_internal_encoding("UTF-8");
       $this->autoRender = false;
       /*
       post params
       post_message:投稿文
       facebook_id:facebook上のID
       user_name:facebook上の名前
       latitude:緯度
       longitude:経度
       image_urls:全ての画像URL
       */

       $msg = $this->request->data('post_message');
       $facebookId = $this->request->data('facebook_id');
       $userName = $this->request->data('user_name');
       $latitude = $this->request->data('latitude');
       $longitude = $this->request->data('longitude');
       $urls = $this->request->data('image_urls');

       /*
       $msg = "testtest";
       $facebookId = "hogehogefacebook";
       $userName = "HOGE";
       $latitude = 42.12435;
       $longitude = 135.85936;
       $urls = array("img1", "img2", "img3");
       */
       debug($msg);
       debug($facebookId);
       debug($userName);
       debug($latitude);
       debug($longitude);
       debug($urls);

       $userId = $this->checkUser($facebookId, $userName);

       $restId = $this->checkRestaurant($latitude, $longitude);

       $postTable = TableRegistry::get('Posts');
       $post = $postTable->newEntity();

       $post->post_message = $this->messageDecode($msg);
       $post->user_id = $userId;
       $post->restaurant_id = $restId;
       date_default_timezone_set('Asia/Tokyo');
       $post->create_date = date('Y/m/d H:i');
       debug($post->create_date);
       $postId = null;

       if ($postTable->save($post)) {
           // $article エンティティは今や id を持っています
           $postId = $post->id;
           debug($postId);
       }

       $urlIds = $this->insertUrls($urls);

       $postsUrlsTable = TableRegistry::get('PostsUrls');
       foreach ($urlIds as $urlId) {
           $postUrl = $postsUrlsTable->newEntity();
           $postUrl->post_id = $postId;
           $postUrl->url_id = $urlId;
           if($postsUrlsTable->save($postUrl)){
               //保存に成功した場合
           }
       }
   }

   public function checkUser($facebookId, $userName){
       $this->autoRender = false;
       debug($facebookId);
       debug($userName);
       $usersTable = TableRegistry::get('Users');
       $users = $usersTable->find('all');
       foreach ($users as $row) {
           if($row->facebook_id == $facebookId){
               return $row->id;
           }
       }
       $user = $usersTable->newEntity();
       $user->facebook_id = $facebookId;
       $user->user_name = $userName;
       $user->post_count = 0;

       if ($usersTable->save($user)) {
           // $article エンティティは今や id を持っています
           $id = $user->id;
           debug($id);
           return $id;
       }
   }

   public function checkRestaurant($latitude, $longitude){
       $this->autoRender = false;
       debug($latitude);
       debug($longitude);
       $restaurantsTable = TableRegistry::get('Restaurants');
       $restaurants = $restaurantsTable->find('all');
       foreach ($restaurants as $row) {
           if($row->restaurant_lon == $longitude  && $row->restaurant_lat == $latitude){
               return $row->id;
           }
       }

       //todo 緯度経度から店名を取得する処理
       $restName = "hoge";

       $restaurant = $restaurantsTable->newEntity();
       $restaurant->restaurant_name = $restName;
       $restaurant->restaurant_lon = $longitude;
       $restaurant->restaurant_lat = $latitude;

       if ($restaurantsTable->save($restaurant)) {
           // $article エンティティは今や id を持っています
           $id = $restaurant->id;
           debug($id);
           return $id;
       }
   }

   public function insertUrls($urls){
       $urlsTable = TableRegistry::get('Urls');
       $urlIdsArray = array();
       foreach ($urls as $url) {
           $oneUrl = $urlsTable->newEntity();
           $oneUrl->image_url = $url;
           if($urlsTable->save($oneUrl)){
               $id = $oneUrl->id;
               $urlIdsArray[] = $id;
           }
       }
       return $urlIdsArray;
   }

   public function messageDecode($msg){
       return mb_convert_encoding($msg,"UTF-8");
   }
   /*
   protected function queryVaridate(array $query){
       //id, post_message, user_id, reataurant_id
       return $query;
   }
   */
}
