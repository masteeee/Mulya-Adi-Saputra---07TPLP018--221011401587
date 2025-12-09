<?php
session_start();
include_once("Database/connect.php");
include_once("includes/language_helper.php");
include_once("header.php");

// Check if order was successful
if (!isset($_SESSION['order_success']) || !isset($_SESSION['order_number'])) {
    header('Location: themes.php');
    exit();
}

// Get order details
$order_number = $_SESSION['order_number'];
$order_query = $con->prepare("SELECT * FROM orders WHERE order_number = ?");
$order_query->bind_param("s", $order_number);
$order_query->execute();
$order = $order_query->get_result()->fetch_assoc();

// Clear the success session
unset($_SESSION['order_success']);
unset($_SESSION['order_number']);
?>

<div class="banner about-bnr">
    <div class="container">
        <h2><?php echo t('order_success'); ?></h2>
    </div>
</div>

<div class="codes">
    <div class="container">
        <div class="alert alert-success text-center">
            <h3><?php echo t('thank_you'); ?></h3>
            <p><?php echo t('order_received'); ?></p>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo t('order_details'); ?></h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4><?php echo t('order_information'); ?></h4>
                        <p><strong><?php echo t('order_number'); ?>:</strong> <?php echo htmlspecialchars($order['order_number']); ?></p>
                        <p><strong><?php echo t('order_date'); ?>:</strong> <?php echo date('d F Y H:i', strtotime($order['order_date'])); ?></p>
                        <p><strong><?php echo t('status'); ?>:</strong> 
                            <?php 
                            $status = [
                                'pending' => t('pending'),
                                'partial_paid' => t('partial_paid'),
                                'paid' => t('paid'),
                                'cancelled' => t('cancelled')
                            ];
                            echo $status[$order['payment_status']] ?? $order['payment_status'];
                            ?>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h4><?php echo t('customer_information'); ?></h4>
                        <p><strong><?php echo t('name'); ?>:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
                        <p><strong><?php echo t('email'); ?>:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
                        <p><strong><?php echo t('phone'); ?>:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
                    </div>
                </div>
                
                <hr>
                
                <h4><?php echo t('order_summary'); ?></h4>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th><?php echo t('item'); ?></th>
                                <th class="text-right"><?php echo t('price'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo htmlspecialchars($order['theme_name']); ?></td>
                                <td class="text-right">Rp <?php echo number_format($order['total_amount'], 0, ',', '.'); ?></td>
                            </tr>
                            <?php if ($order['payment_status'] === 'partial_paid'): ?>
                            <tr>
                                <td colspan="2" class="text-right">
                                    <strong><?php echo t('amount_paid'); ?>: </strong>
                                    Rp <?php 
                                    $payment_query = $con->query("SELECT SUM(amount) as total_paid FROM payments WHERE order_id = {$order['id']}");
                                    $total_paid = $payment_query->fetch_assoc()['total_paid'];
                                    echo number_format($total_paid, 0, ',', '.');
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-right">
                                    <strong><?php echo t('remaining_balance'); ?>: </strong>
                                    Rp <?php echo number_format($order['total_amount'] - $total_paid, 0, ',', '.'); ?>
                                </td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <td colspan="2" class="text-right">
                                    <strong><?php echo t('payment_instructions'); ?>:</strong><br>
                                    <?php echo nl2br(htmlspecialchars(t('bank_transfer_instructions'))); ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="text-center">
                    <a href="index.php" class="btn btn-primary"><?php echo t('back_to_home'); ?></a>
                    <a href="my_orders.php" class="btn btn-default"><?php echo t('view_my_orders'); ?></a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once("footer.php"); ?>
