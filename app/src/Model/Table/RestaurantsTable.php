<?php
namespace App\Model\Table;
use Cake\ORM\Table;

class RestaurantsTable extends Table{
  public function initialize(array $config){
    $this->table("Restaurants");
  }
}
