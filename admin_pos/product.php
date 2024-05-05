<?php
include('connection.php');
$action = isset($_GET['action']) ? $_GET['action'] : '';

//If user checkout item
if ($action == 'checkout') saveProducts();

function getProducts(){  
    //Get connection available
    $conn = $GLOBALS['conn'];

    //Query all products
    $stmt = $conn->prepare("SELECT * FROM coffee");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //Return rows
    return $rows;
}

function saveProducts() {
    try {
        //Get connection variable
        $conn = $GLOBALS['conn_pos'];

        $data = $_POST['data'];
        $customer = $_POST['customer'];


        //Insert to customer
        $sql = "INSERT INTO customers (nick_name, date_created, date_updated) VALUES (:nick_name, :date_created, :date_updated)";
            $db_arr = [
                'nick_name' => $customer['nickName'],
                'date_created' => date("Y-m-d H:i:s"),
                'date_updated' => date("Y-m-d H:i:s"),
            ];
            $stmt = $conn->prepare($sql);
            $stmt->execute($db_arr);

            $customer_id = $conn->lastInsertId();

        //Insert to sales
        $sql = "INSERT INTO sales(customer_id, user_id, total_amount, amount_tendered, change_amt, date_created, date_updated) 
            VALUES (:customer_id, :user_id, :total_amount, :amount_tendered, :change_amt, :date_created, :date_updated)";

            $total_amount = $_POST['totalAmt'];
            $change_amt = $_POST['change'];
            $tenderedAmt =  $_POST['tenderedAmt'];
            $user_id = 1;


            $db_arr = [
                'customer_id' => $customer_id, 
                'user_id' => $user_id, //hard code for now
                'total_amount' => $total_amount, 
                'amount_tendered' => $tenderedAmt, 
                'change_amt' => $change_amt, 
                'date_created' => date("Y-m-d H:i:s"), 
                'date_updated' => date("Y-m-d H:i:s")
            ];
            $stmt = $conn->prepare($sql);
            $stmt->execute($db_arr);
            $sales_id = $conn->lastInsertId();


        //Insert order item
        foreach( $data as $product_id => $order_item) {
        
        //Insert to sales_item
        $sql = "INSERT INTO sales_items(sales_id, product_id, quantity, unit_price, sub_total, date_created, date_updated) 
        VALUES (:sales_id, :product_id, :quantity, :unit_price, :sub_total, :date_created, :date_updated)";

        $db_arr = [
            'sales_id' => $sales_id, 
            'product_id'=> $product_id, 
            'quantity' => $order_item ['orderQty'], 
            'unit_price' => $order_item ['price'], 
            'sub_total' => $order_item ['amount'], 
            'date_created' => date("Y-m-d H:i:s"), 
            'date_updated' => date("Y-m-d H:i:s")
        ];
        $stmt = $conn->prepare($sql);
        $stmt->execute($db_arr);

        
        }

        echo json_encode([
            'success' => true,
            'id' => $sales_id,
            'message' => 'Order successfully checkout!',
            'products' => getProducts()
        ]);

    }catch(Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }

}
?>