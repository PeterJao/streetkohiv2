<?php
    include('connection.php');


    function getSaleCustomer($customer_id){
        $conn = $GLOBALS['conn_pos'];
        $stmt = $conn->prepare("
                SELECT * FROM customers
                    where id = $customer_id
        ");

        $stmt->execute();
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);
        return $customer;
    }

    function getOrderItems($id)
    {
        //Query items
        $conn = $GLOBALS['conn_pos'];
        $stmt = $conn->prepare("SELECT * FROM sales_items WHERE sales_id=$id");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $rows;
    }

    function getSale($sale_id)
    {
        $conn = $GLOBALS['conn_pos'];
        $stmt = $conn->prepare("
                SELECT * FROM sales WHERE sales.id=$sale_id
        ");
        $stmt->execute();
        $sale = $stmt->fetch(PDO::FETCH_ASSOC);

        //Get customers data
        $customers_data = getSaleCustomer($sale['customer_id']);

        //Get order items data
        $items = getOrderItems($sale['id']);
        $items_data = [];

        $inv_conn = $GLOBALS['conn'];
        foreach($items as $item){
            $pid = $item['product_id'];
            
            $stmt = $inv_conn->prepare("
                        SELECT coffee.coffee_name FROM coffee
                        where coffee_id = $pid
                    ");
            $stmt->execute();
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            $items_data[$item['id']] = $item;
            $items_data[$item['id']]['product'] = $product['coffee_name'];
        }

        return [
            'sales' => $sale,
            'items' => $items_data,
            'customer' => $customers_data
        ];
    }