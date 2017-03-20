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

    public function getRanking(){
        $this->autoRender = false;
        $faceId = $this->request->data("facebook_id");

        $usersTable = TableRegistry::get("Users");
        $users = $usersTable->find("all")->order([
            "Users.post_count" => "DESC"
        ]);
        $rank =1;
        foreach ($users as $user){
            if($user->facebook_id === $faceId){
                $return = array("rank"=>$rank,"count"=>$user->post_count);

                $this->response->charset('UTF-8');
                $this->response->type('json');
                echo json_encode($return, JSON_UNESCAPED_UNICODE);

            }
            $rank++;
        }


    }
}
