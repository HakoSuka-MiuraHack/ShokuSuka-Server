<?php
/**
 * Created by IntelliJ IDEA.
 * User: taross
 * Date: 2017/03/20
 * Time: 2:31
 */

namespace App\Controller;


class PostsUrlsController extends AppController {
    public function post() {
        $params = $this->queryVaridate($this->request->getQuery());//クエリパラメータ取得

        $post = TableRegistry::get("PostUrl");
        $entity = $post->patchEntity($post->newEntity(), $params);// エンティティを作成してバリデートしたクエリパラメータをセット
        $result = $post->save($entity);
    }
}
