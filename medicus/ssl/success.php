<!DOCTYPE html>
<title>Success Page</title>
<h1>SSLCOMMERZ Integration Success Page</h1>
<?php
session_start();
include("connection.php");
include("SSLCommerz.php");

$sslc = new SSLCommerz();
$tran_id = $_SESSION['payment_values']['tran_id'];
$amount = $_SESSION['payment_values']['amount'];
$currency = $_SESSION['payment_values']['currency'];

$sql = "select * from orders WHERE transaction_id='".$tran_id."'";
$result = $conn_integration->query($sql);
$row = $result->fetch_array(MYSQLI_ASSOC);
if($row['status'] == 'Pending') 
{

        $validation = $sslc->orderValidate($tran_id, $amount, $currency, $_POST);
        $tran_id = (string)$tran_id;
        if ($validation == TRUE) 
        {
             $sql = "UPDATE orders SET status='Success' WHERE transaction_id='$tran_id'";

            if ($conn_integration->query($sql) === TRUE) 
            {
                echo "Payment Record Updated Successfully";
            } 
            else 
            {
                echo "Error updating record: " . $conn_integration->error;
            }

            echo "<h2 style='color: green; text-align: center;'>Congratulations! Your Transaction is Successful.</h2>";
            ?>
            <table border="1" class="table">
                <thead>
                <tr>
                    <th colspan="2">Payment Status</th>
                </tr>
                </thead>
                <tr>
                    <td>Transaction ID</td>
                    <td><?php echo $_POST['tran_id'] ?></td>
                </tr>
                <tr>
                    <td>Card Type</td>
                    <td><?php echo $_POST['card_type'] ?></td>
                </tr>
                <tr>
                    <td>Bank Transaction ID</td>
                    <td><?php echo $_POST['bank_tran_id'] ?></td>
                </tr>
                <tr>
                    <td>Card Type</td>
                    <td><?php echo $_POST['card_type'] ?></td>
                </tr>
                <tr>
                    <td>Amount</td>
                    <td><?php echo $_POST['currency_amount'] ?></td>
                </tr>
            </table>
        <?php
        } 
        else 
        {
            echo $sql = "UPDATE orders SET status='Failed' WHERE transaction_id='$tran_id'";
            echo "<h2 style='color: #ff0000; text-align: center'>Payment was not valid. Please contact with the merchant.</h2>";
        }
        unset($_SESSION['payment_values']);
}
else if($row['status'] == 'Success') 
{
    echo "These order is already Successful";
}
else
{
    echo "Invalid Information";
}    
?>