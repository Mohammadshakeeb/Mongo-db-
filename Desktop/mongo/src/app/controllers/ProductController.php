<?php

use Phalcon\Mvc\Controller;

class ProductController extends Controller
{

    public function IndexAction()
    {
        // return "Signup";
    }
    public function addAction()
    {
    }

    public function addhelperAction()
    {
        $val = $_POST;
        echo "<pre>";
        // print_r($val);
        $main=array_splice($_POST, 0, 5);
        echo "<pre>";
        // print_r($main);
        
        // $c= count($val['title']);
        // echo $c;
        $field=array();
        for ($i = 0; $i < count($val['title']); $i += 2) {
            // array_push($field, $val['title'][$i]);
            $field+=[$val['title'][$i] => $val['title'][$i+1]];
        }
        echo "<pre>";
        // print_r($field);

        $variations=array();
        for ($i = 0; $i < count($val['var']); $i += 3) {
            // array_push($field, $val['title'][$i]);
            $vari=array();
            array_push($vari, $val['var'][$i], $val['var'][$i+1], $val['var'][$i+2]);
            // $variations+=[array() => $vari];
            array_push($variations, $vari);
        }
        echo "<pre>";
        // print_r($variations);
        // $insert=array();
        // array_push($insert, $main, $field, $variations);
       
        $this->mongo->insertOne([
            "Details" => $main,
            "Additional Fields"=> $field,
            "Variations" => $variations

         ]);
        
         $this->response->redirect("product/displayproducts");
        // // echo "added successfully";
    }
    public function displayproductsAction(){
        // if(isset($_POST)){
        //     // echo "<pre>";
        //     // print_r($_POST);
        $data = $this->mongo->find();
        //     // die;
            if ($this->request->getPost('search')) {
                $productsearch = $this->request->getPost('name');
    
                foreach ($data as $v) {
                    $v = json_decode(json_encode($v, true), true);
                    if (strtolower( $v['Details']['name']) == strtolower($productsearch)) {
                        $this->view->product =  $v;
                    }
                }
            }
        // }
    }

    public function editproductAction(){

    }

    public function edithelperAction(){


        $val = $_POST;
        $id=$_POST['id'];
        // echo $id;
        // die;
        echo "<pre>";
        // print_r($val);
        $main=array_splice($_POST, 0, 5);
        echo "<pre>";
        // print_r($main);

        
        // $c= count($val['title']);
        // echo $c;
        $field=array();
        for ($i = 0; $i < count($val['title']); $i += 2) {
            // array_push($field, $val['title'][$i]);
            $field+=[$val['title'][$i] => $val['title'][$i+1]];
        }
        echo "<pre>";
        // print_r($field);

        $variations=array();
        for ($i = 0; $i < count($val['var']); $i += 3) {
            // array_push($field, $val['title'][$i]);
            $vari=array();
            array_push($vari, $val['var'][$i], $val['var'][$i+1], $val['var'][$i+2]);
            // $variations+=[array() => $vari];
            array_push($variations, $vari);
        }
        echo "<pre>";
        $updateResult = $this->mongo->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($id)],
            ['$set' => [
                "Details" => $main,
                "Additional Fields"=> $field,
                "Variations" => $variations
            ]]
);
    echo "update successfully";
    }

    public function deleteproductAction(){

        $id=$_GET['id'];
        echo $id;
        $document=[
            "_id" => new MongoDB\BSON\ObjectId($id)
        ];
        $delete=$this->mongo->deleteOne($document);
        echo "deleted successfully";
    }
}
