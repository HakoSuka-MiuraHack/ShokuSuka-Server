<?php
/**
 * Created by IntelliJ IDEA.
 * User: taross
 * Date: 2017/03/20
 * Time: 3:15
 */

namespace App\Controller;
use Cake\ORM\TableRegistry;

class UsersController extends AppController {
    public function post() {
        if(TableRegistry::get()->find($this->request->getQuery('facebook_id')) === -1){
            $params = $this->queryVaridate($this->request->getQuery());
            $insert = [
                "facebook_id" => $this->request->getQuery('facebook_id'),
                "user_name" => $this->request->getQuery('user_name'),
                "post_count" => 0
            ];

            $post = TableRegistry::get("User");
            $entity = $post->patchEntity($post->newEntity(), $params);// エンティティを作成してバリデートしたクエリパラメータをセット
            $result = $post->save($entity);
        }
    }
}
