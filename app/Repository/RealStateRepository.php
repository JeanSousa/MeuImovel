<?php
namespace App\Repository;

class RealStateRepository extends AbstractRepository
{
    private $location;

    public function setLocation(array $data) : self
    {
        $this->location = $data;
        return $this;
    }  
 

    public function getResult()
    {  
        $location = $this->location;
        
        //com whereHas faço uma condição em cima da relação address
        return $this->model->whereHas('address', function($q) use ($location){
            $q->where('state_id', $location['state'])
            ->where('city_id', $location['city']);
        });
    }
}