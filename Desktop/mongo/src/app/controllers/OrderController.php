<?php

use Phalcon\Mvc\Controller;

class OrderController extends Controller
{

    public function IndexAction()
    {
        // $data = $this->mongo->find();
        // foreach($data as $k => $v){
        // $data = json_decode(json_encode($data, true), true);
        // echo "<pre>";
        //  print_r($v);
        //  die;
        // $this->view->data=$data;
        if (!is_null($this->request->getpost('product'))) {
            $this->view->id=$this->request->getpost();
            $id=$this->request->getpost('product');
            $variations=$this->mongo->product->findOne(["_id"=>new MongoDB\BSON\ObjectId($id)]);
            $variations = json_decode(json_encode($variations, true), true);
            $this->view->vari=$variations;
        }
    }

    public function getproductAction(){

        $id=$this->request->getpost('product_id');
        $variations=$this->mongo->product->findOne(["_id"=>new MongoDB\BSON\ObjectId($id)]);
        $data=json_encode($variations);
        return $data;

    }
    public function orderAction(){

       
         $id=$this->request->getpost('product');
         $variations=$this->mongo->product->findOne(["_id"=>new MongoDB\BSON\ObjectId($id)]);
         $variations = json_decode(json_encode($variations, true), true);
         echo "<pre>";
        //  print_r($variations);
         
         $this->mongo->orders->insertOne([
            "Product Name" => $variations['Details']['name'],
            "Varient"=> $this->request->getpost('variant'),
            "Customer Name" => $this->request->getpost('customer_name'),
            "Quantity" => $this->request->getpost('quantity'),
            "Date" => date('d/m/Y')

         ]);
         echo "order added sucessfully";
         header('location:http://localhost:8080/order/orderlisting');

    }
    public function orderlistingAction(){

        $orders = $this->mongo->orders->find();
        $orders = json_decode(json_encode($orders, true), true);
        echo "<pre>";
            print_r($orders);
            die;

        if ($this->request->getPost('filter_by_status')) {
            $filter_by_status = $this->request->getPost('filter_by_status');
            $orders = array('status' => $filter_by_status);
            $orders = $this->mongo->orders->find($orders);
            $this->view->orders = $orders;
           
        }
        // else {
        //     $this->view->orders = $orders;
        // }

        if ($this->request->getPost('filter_by_date')) {
            $filter_by_date = $this->request->getPost('filter_by_date');
            if ($filter_by_date == 'today') {
                $orders = array('order_date' => date('Y/m/d'));
                $orders = $this->mongo->orders->find($orders);
                $this->view->orders = $orders;
            }
            if ($filter_by_date == 'this_week') {
                $start_date = date("Y/m/d", strtotime("-1 week"));
                $end_date = date("Y/m/d");
                $orders = array('order_date' => ['$gte' => $start_date, '$lte' => $end_date]);
                $orders = $this->mongo->orders->find($orders);
                $this->view->orders = $orders;
            }
            if ($filter_by_date == 'this_month') {
                $start_date = date("Y/m/d", strtotime("first day of this month"));
                $end_date = date("Y/m/d");
                $order = array('order_date' => ['$gte' => $start_date, '$lte' => $end_date]);
                $orders = $this->mongo->orders->find($order);
                // foreach($orders as $k=>$v){
                //     print_r($v);
                // }
                // die;
                $this->view->orders = $orders;
            }
        }



        if ($this->request->getPost('status')) {

            $id = $this->request->getPost('id');
            $status = $this->request->getPost('status');
            $postdata = array(
                "status" => $status

            );
            $this->mongo->orders->updateOne(["_id" => new MongoDB\BSON\ObjectID($id)], ['$set' => $postdata]);
            $this->response->redirect('/order/list');
        } else {
            $this->view->orders = $orders;
            // $orders = json_decode(json_encode($orders, true), true);
            // echo "<pre>";
            // print_r($orders);
            // die;
        }
    }
}

    

