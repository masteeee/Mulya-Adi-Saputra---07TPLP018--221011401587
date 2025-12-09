<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include necessary files
require_once('Database/connect.php');
require_once('session.php');
require_once("includes/language_helper.php");

// Initialize variables
$errors = [];
$success_message = '';
$theme = [];
$show_form = true;
$from_temp = false;
$empty_cart = false;

// Check if we have a theme ID in the URL or in the temp table
$theme_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// If no theme ID in URL, check temp table
if ($theme_id === 0) {
    $result = $con->query("SELECT * FROM temp LIMIT 1");
    if ($result && $result->num_rows > 0) {
        $temp = $result->fetch_assoc();
        $theme = [
            'id' => $temp['id'],
            'name' => $temp['nm'],
            'price' => $temp['price'],
            'image' => $temp['img']
        ];
        $from_temp = true;
        $theme_id = $temp['id'];
    } else {
        // No theme in temp table and no ID provided, show empty cart message
        $show_form = false;
        $empty_cart = true;
    }
} else {
    // Get theme details from database
    $result = $con->query("SELECT * FROM themes WHERE id = $theme_id");
    if ($result && $result->num_rows > 0) {
        $theme = $result->fetch_assoc();
    } else {
        // Try to get from anniversary table
        $result = $con->query("SELECT * FROM anniversary WHERE id = $theme_id");
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $theme = [
                'id' => $row['id'],
                'name' => $row['name'],
                'price' => $row['price'],
                'image' => $row['image']
            ];
        } else {
            $_SESSION['error'] = t('theme_not_found');
            header('Location: themes.php');
            exit();
        }
    }
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Basic validation
    $customer_name = trim($_POST['nm'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['mo'] ?? '');
    $date = trim($_POST['date'] ?? '');
    $theme_id = intval($_POST['theme_id'] ?? 0);
    $theme_name = trim($_POST['theme_name'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    
    // Validate inputs
    if (empty($customer_name)) $errors[] = t('name_required');
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = t('invalid_email');
    if (empty($date)) $errors[] = t('date_required');
    if (empty($phone)) $errors[] = t('phone_required');
    
    // If no errors, process the booking
    if (empty($errors)) {
        // Get theme details from temp table
        $result = $con->query("SELECT * FROM temp LIMIT 1");
        if ($result && $result->num_rows > 0) {
            $temp = $result->fetch_assoc();
            
            // Insert into booking table
            $stmt = $con->prepare("INSERT INTO booking (nm, email, mo, theme, thm_nm, price, date) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssds", $customer_name, $email, $phone, $temp['img'], $temp['nm'], $temp['price'], $date);
            
            if ($stmt->execute()) {
                // Clear the temp table after successful booking
                $con->query("TRUNCATE TABLE temp");
                
                // Set success message and redirect
                $_SESSION['success'] = t('event_booked');
                header('Location: index.php');
                exit();
            } else {
                $errors[] = t('booking_failed');
            }
        } else {
            $errors[] = t('theme_not_found');
        }
    }
}

// Include header after all processing is done
include("header.php");

// Show empty cart message if no items
if ($empty_cart) {
    ?>
    <div class="container">
        <div class="alert alert-info">
            <h2><?php echo t('your_cart_is_empty'); ?></h2>
            <p><?php echo t('please_select_a_theme_first'); ?></p>
            <a href="themes.php" class="btn btn-primary"><?php echo t('browse_themes'); ?></a>
        </div>
    </div>
    <?php 
    include("footer.php");
    exit();
}

// Only show the form if we have a theme
if ($show_form && !empty($theme)) { 
    // Get theme details from temp table if coming from temp
    if ($from_temp) {
        $qry = $con->query("SELECT * FROM temp WHERE id = " . $theme_id);
        if ($qry && $qry->num_rows > 0) {
            $row = $qry->fetch_assoc();
        } else {
            $show_form = false;
            $empty_cart = true;
            include("footer.php");
            exit();
        }
    }
?>

<style>
    /* Main Container */
    .cart-container {
        margin: 30px auto;
        max-width: 1200px;
        padding: 0 15px;
    }
    
    /* Header */
    .cart-header {
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }
    .cart-header h2 {
        color: #333;
        font-weight: 600;
        margin: 0;
    }
    
    /* Panels */
    .panel {
        border: 1px solid #e0e0e0;
        border-radius: 4px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        margin-bottom: 20px;
    }
    .panel-heading {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e0e0e0;
        padding: 15px 20px;
    }
    .panel-title {
        color: #333;
        font-size: 16px;
        font-weight: 600;
        margin: 0;
    }
    .panel-body {
        padding: 20px;
    }
    
    /* Form Elements */
    .form-group {
        margin-bottom: 20px;
    }
    label.control-label {
        font-weight: 500;
        color: #555;
        padding-top: 7px;
    }
    .form-control1 {
        border: 1px solid #ddd;
        border-radius: 4px;
        box-shadow: none;
        height: 38px;
        padding: 6px 12px;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        width: 100%;
    }
    .form-control1:focus {
        border-color: #66afe9;
        box-shadow: 0 0 8px rgba(102, 175, 233, 0.6);
        outline: 0;
    }
    textarea.form-control1 {
        height: auto;
        min-height: 100px;
    }
    
    /* Buttons */
    .btn {
        border-radius: 4px;
        font-weight: 500;
        padding: 8px 16px;
        transition: all 0.3s ease;
    }
    .btn-primary {
        background-color: #4285f4;
        border: none;
        color: white;
    }
    .btn-primary:hover, .btn-primary:focus {
        background-color: #3367d6;
        color: white;
    }
    .btn-default {
        background-color: #f5f5f5;
        border: 1px solid #ddd;
        color: #333;
    }
    .btn-default:hover, .btn-default:focus {
        background-color: #e0e0e0;
        color: #333;
    }
    .btn-lg {
        padding: 10px 24px;
    }
    
    /* Alerts */
    .alert {
        border-radius: 4px;
        margin: 20px 0;
        padding: 15px;
    }
    .alert-danger {
        background-color: #fde8e8;
        border: 1px solid #f5c6cb;
        color: #721c24;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .panel-body {
            padding: 15px;
        }
        .col-sm-offset-2 {
            margin-left: 0;
        }
        .btn {
            display: block;
            margin-bottom: 10px;
            width: 100%;
        }
    }
</style>

<script>
$(document).ready(function() {
    $("#datepicker").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd',
        minDate: 0
    });
    
    // Add form validation
    $('form').on('submit', function(e) {
        let isValid = true;
        $('.required').each(function() {
            if ($(this).val() === '') {
                $(this).addClass('is-invalid');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        return isValid;
    });
});
</script>
    $payment_proof = '';
    if (empty($errors) && isset($_FILES['payment_proof']) && $_FILES['payment_proof']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/payments/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        $file_ext = strtolower(pathinfo($_FILES['payment_proof']['name'], PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'pdf'];
        
        if (in_array($file_ext, $allowed_ext)) {
            $file_name = uniqid('payment_') . '.' . $file_ext;
            $target_file = $upload_dir . $file_name;
            
            if (move_uploaded_file($_FILES['payment_proof']['tmp_name'], $target_file)) {
                $payment_proof = $file_name;
            } else {
                $errors[] = t('upload_failed');
            }
        } else {
            $errors[] = t('invalid_file_type');
        }
    } elseif (empty($errors)) {
        $errors[] = t('payment_proof_required');
    }
    
    // If no errors, process the order
    if (empty($errors)) {
        // Calculate amounts
        $deposit_percent = $payment_type === 'deposit_50' ? 0.5 : ($payment_type === 'deposit_75' ? 0.75 : 1);
        $deposit_amount = $price * $deposit_percent;
        $order_number = 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid());
        
        // Start transaction
        mysqli_begin_transaction($con);
        
        try {
            // Insert order
            $stmt = $con->prepare("INSERT INTO orders (order_number, customer_name, email, phone, theme_id, theme_name, total_amount) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssisd", $order_number, $customer_name, $email, $phone, $theme_id, $theme_name, $price);
            $stmt->execute();
            $order_id = $con->insert_id;
            
            // Insert payment
            $stmt = $con->prepare("INSERT INTO payments (order_id, payment_type, amount, payment_proof) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isds", $order_id, $payment_type, $deposit_amount, $payment_proof);
            $stmt->execute();
            
            // Update order status
            $status = $payment_type === 'full_payment' ? 'paid' : 'partial_paid';
            $stmt = $con->prepare("UPDATE orders SET payment_status = ? WHERE id = ?");
            $stmt->bind_param("si", $status, $order_id);
            $stmt->execute();
            
            // Commit transaction
            mysqli_commit($con);
            
            // Redirect to thank you page
            $_SESSION['order_success'] = true;
            $_SESSION['order_number'] = $order_number;
            header('Location: order_success.php');
            exit();
        } catch (Exception $e) {
            // Rollback transaction on error
            mysqli_rollback($con);
            $errors[] = t('order_failed') . ': ' . $e->getMessage();
        }
    }
}

// Include header after all processing is done
include("header.php");
    exit();
}
?>

<?php if ($empty_cart): ?>
    <div class="container">
        <div class="alert alert-info">
            <h2><?php echo t('your_cart_is_empty'); ?></h2>
            <p><?php echo t('please_select_a_theme_first'); ?></p>
            <a href="themes.php" class="btn btn-primary"><?php echo t('browse_themes'); ?></a>
        </div>
    </div>
<?php } else { ?>
    <div class="container cart-container">
        <div class="row">
            <div class="col-md-12">
                <div class="cart-header">
                    <h2><?php echo t('order_summary'); ?></h2>
                </div>
                
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <h4><i class="fa fa-exclamation-triangle"></i> <?php echo t('error_occurred'); ?></h4>
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="theme_id" value="<?php echo $theme['id']; ?>">
                    <input type="hidden" name="theme_name" value="<?php echo htmlspecialchars($theme['name']); ?>">
                    <input type="hidden" name="price" value="<?php echo $theme['price']; ?>">
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><?php echo t('customer_information'); ?></h3>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label"><?php echo t('full_name'); ?>:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control1 required" name="nm" value="<?php echo isset($_POST['nm']) ? htmlspecialchars($_POST['nm']) : ''; ?>" required>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-sm-4 control-label"><?php echo t('email'); ?>:</label>
                                    <div class="col-sm-8">
                                        <input type="email" class="form-control1 required" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-sm-4 control-label"><?php echo t('phone'); ?>:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control1 required" name="mo" 
                                               value="<?php echo isset($_POST['mo']) ? htmlspecialchars($_POST['mo']) : ''; ?>" 
                                               pattern="08[0-9]{8,11}" 
                                               title="<?php echo t('phone_format_hint'); ?>" 
                                               required>
                                        <small class="text-muted"><?php echo t('example_phone'); ?>: 08123456789</small>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-sm-4 control-label"><?php echo t('event_date'); ?>:</label>
                                    <div class="col-sm-8">
                                        <input type="date" class="form-control1 required" name="date" id="datepicker" 
                                               value="<?php echo isset($_POST['date']) ? htmlspecialchars($_POST['date']) : ''; ?>" 
                                               min="<?php echo date('Y-m-d'); ?>" 
                                               required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><?php echo t('theme_details'); ?></h3>
                            </div>
                            <div class="panel-body text-center">
                                <img src="./images/<?php echo htmlspecialchars($theme['image']); ?>" 
                                     class="img-responsive img-thumbnail" 
                                     style="max-height: 200px; margin-bottom: 15px;">
                                
                                <h4><?php echo htmlspecialchars($theme['name']); ?></h4>
                                <h3 class="text-primary">Rp <?php echo number_format($theme['price'], 0, ',', '.'); ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><?php echo t('payment_information'); ?></h3>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><?php echo t('payment_option'); ?>:</label>
                                    <div class="col-sm-10">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="payment_type" value="deposit_50" required> 
                                                <strong><?php echo t('deposit_50_percent'); ?></strong> 
                                                (Rp <?php echo number_format($theme['price'] * 0.5, 0, ',', '.'); ?>) - 
                                                <?php echo t('pay_later'); ?>
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="payment_type" value="deposit_75"> 
                                                <strong><?php echo t('deposit_75_percent'); ?></strong> 
                                                (Rp <?php echo number_format($theme['price'] * 0.75, 0, ',', '.'); ?>) - 
                                                <?php echo t('pay_later'); ?>
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="payment_type" value="full_payment"> 
                                                <strong><?php echo t('full_payment'); ?></strong> 
                                                (Rp <?php echo number_format($theme['price'], 0, ',', '.'); ?>) - 
                                                <span class="text-success"><?php echo t('discount_5_percent'); ?></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="payment_proof" class="col-sm-2 control-label"><?php echo t('payment_proof'); ?>:</label>
                                    <div class="col-sm-10">
                                        <input type="file" class="form-control1" name="payment_proof" id="payment_proof" 
                                               accept="image/*,.pdf" required>
                                        <small class="text-muted">
                                            <?php echo t('upload_payment_proof'); ?> 
                                            (JPG, PNG, PDF, <?php echo t('max_size'); ?> 2MB)
                                        </small>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="submit" name="submit" class="btn btn-primary btn-lg">
                                            <i class="fa fa-check"></i> <?php echo t('submit_payment'); ?>
                                        </button>
                                        <a href="themes.php" class="btn btn-default">
                                            <i class="fa fa-arrow-left"></i> <?php echo t('back_to_themes'); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
						<div class="form-group">
								<label for="focusedinput" class="col-sm-2 control-label"><?php echo t('full_name'); ?></label>
								<div class="col-sm-8">
									<input type="text" class="form-control1"  name="nm" pattern="[A-Za-z\s]{2,30}" title="Only Letter For Name" id="focusedinput" placeholder="<?php echo t('full_name'); ?>" required="">
								</div>
							</div>
							<div class="form-group">
								<label for="smallinput" class="col-sm-2 control-label label-input-sm"><?php echo t('email_address'); ?></label>
								<div class="col-sm-8">
									<input type="text" class="form-control1 input-sm" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" title="Enter Proper Email Id" id="smallinput" placeholder="<?php echo t('email_address'); ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="smallinput" class="col-sm-2 control-label label-input-sm"><?php echo t('mobile_number'); ?></label>
								<div class="col-sm-8">
									<input type="text" class="form-control1 input-sm" name="mo" id="smallinput" 
									   pattern="08[0-9]{8,11}" 
									   title="<?php echo t('mobile_number_format'); ?>" 
									   maxlength="13" 
									   placeholder="08123456789" 
									   oninput="this.value = this.value.replace(/[^0-9]/g, '');"
									   required=""/>
									<small class="text-muted"><?php echo t('example_phone'); ?>: 08123456789</small>
								</div>
							</div>
						<div class="form-group">
							<label class="col-sm-2 control-label"><?php echo t('your_theme'); ?>:</label>
							<div class="col-sm-8">
								<img src="./images/<?php echo htmlspecialchars($theme['image']); ?>" height="200" width="300" class="img-thumbnail">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label"><?php echo t('theme_name'); ?>:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control1" value="<?php echo htmlspecialchars($theme['name']); ?>" disabled>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label"><?php echo t('price'); ?>:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control1" value="Rp <?php echo number_format($theme['price'], 0, ',', '.'); ?>" disabled>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-2 control-label"><?php echo t('payment_option'); ?>:</label>
							<div class="col-sm-8">
								<div class="radio">
									<label>
										<input type="radio" name="payment_type" value="deposit_50" required> 
										<?php echo t('deposit_50_percent'); ?> (Rp <?php echo number_format($theme['price'] * 0.5, 0, ',', '.'); ?>) - <?php echo t('pay_later'); ?>
									</label>
								</div>
								<div class="radio">
									<label>
										<input type="radio" name="payment_type" value="deposit_75"> 
										<?php echo t('deposit_75_percent'); ?> (Rp <?php echo number_format($theme['price'] * 0.75, 0, ',', '.'); ?>) - <?php echo t('pay_later'); ?>
									</label>
								</div>
								<div class="radio">
									<label>
										<input type="radio" name="payment_type" value="full_payment"> 
										<?php echo t('full_payment'); ?> (Rp <?php echo number_format($theme['price'], 0, ',', '.'); ?>) - <?php echo t('discount_5_percent'); ?>
									</label>
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<label for="payment_proof" class="col-sm-2 control-label"><?php echo t('payment_proof'); ?>:</label>
							<div class="col-sm-8">
								<input type="file" class="form-control1" name="payment_proof" id="payment_proof" accept="image/*,.pdf" required>
								<small class="text-muted"><?php echo t('upload_payment_proof'); ?> (JPG, PNG, PDF, max 2MB)</small>
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-8">
								<button type="submit" name="submit" class="btn btn-primary"><?php echo t('submit_payment'); ?></button>
								<a href="themes.php" class="btn btn-default"><?php echo t('back_to_themes'); ?></a>
							</div>
						</div>
							</div>
							<div class="form-group">
								<label for="disabledinput" class="col-sm-2 control-label"><?php echo t('price'); ?> :</label>
								<div class="col-sm-8">
								<input disabled="" type="text" class="form-control1" value="<?php echo isset($row['price']) ? number_format($row['price'], 0, ',', '.') : number_format($theme['price'], 0, ',', '.'); ?>" name="price" id="focusedinput" placeholder="<?php echo t('price'); ?>" >
								</div>
							</div>
							<div class="form-group">
								<label for="smallinput" class="col-sm-2 control-label label-input-sm"><?php echo t('event_date'); ?></label>
								<div class="col-sm-8">
									<input type="date" class="form-control1 input-sm" name="date" id="smallinput" placeholder="DD/MM/YYYY" required=""/>
								</div>
							</div>
					<div class="contact-w3form" align="center">
					<a href="book.php">
					<input type="submit" name="submit" class="btn" value="<?php echo t('book_now'); ?>"></a>
					</div>
					</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <button type="submit" name="submit" class="btn btn-primary">
                                        <?php echo t('complete_booking'); ?>
                    </div>
                    <div class="form-group">
                        <label for="smallinput" class="col-sm-2 control-label label-input-sm"><?php echo t('email_address'); ?></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control1 input-sm" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" title="Enter Proper Email Id" id="smallinput" placeholder="<?php echo t('email_address'); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="smallinput" class="col-sm-2 control-label label-input-sm"><?php echo t('mobile_number'); ?></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control1 input-sm" name="mo" id="smallinput" 
                                   pattern="08[0-9]{8,11}" 
                                   title="<?php echo t('mobile_number_format'); ?>" 
                                   maxlength="13" 
                                   placeholder="08123456789" 
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                   required=""/>
                            <small class="text-muted"><?php echo t('example_phone'); ?>: 08123456789</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo t('your_theme'); ?>:</label>
                        <div class="col-sm-8">
                            <img src="./images/<?php echo htmlspecialchars($theme['image']); ?>" height="200" width="300" class="img-thumbnail">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo t('theme_name'); ?>:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control1" value="<?php echo htmlspecialchars($theme['name']); ?>" disabled>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo t('price'); ?>:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control1" value="Rp <?php echo number_format($theme['price'], 0, ',', '.'); ?>" disabled>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo t('payment_option'); ?>:</label>
                <div class="col-sm-8">
                    <div class="radio">
                        <label>
                            <input type="radio" name="payment_type" value="deposit_50" required> 
                            <?php echo t('deposit_50_percent'); ?> (Rp <?php echo number_format($theme['price'] * 0.5, 0, ',', '.'); ?>) - <?php echo t('pay_later'); ?>
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="payment_type" value="deposit_75"> 
                            <?php echo t('deposit_75_percent'); ?> (Rp <?php echo number_format($theme['price'] * 0.75, 0, ',', '.'); ?>) - <?php echo t('pay_later'); ?>
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="payment_type" value="full_payment"> 
                            <?php echo t('full_payment'); ?> (Rp <?php echo number_format($theme['price'], 0, ',', '.'); ?>) - <?php echo t('discount_5_percent'); ?>
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="payment_proof" class="col-sm-2 control-label"><?php echo t('payment_proof'); ?>:</label>
                <div class="col-sm-8">
                    <input type="file" class="form-control1" name="payment_proof" id="payment_proof" accept="image/*,.pdf" required>
                    <small class="text-muted"><?php echo t('upload_payment_proof'); ?> (JPG, PNG, PDF, max 2MB)</small>
                </div>
            </div>
            
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-8">
                    <button type="submit" name="submit" class="btn btn-primary"><?php echo t('submit_payment'); ?></button>
                    <a href="themes.php" class="btn btn-default"><?php echo t('back_to_themes'); ?></a>
                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" name="submit" class="btn btn-primary btn-lg">
                            <i class="fa fa-check"></i> <?php echo t('submit_payment'); ?>
                        </button>
                        <a href="themes.php" class="btn btn-default">
                            <i class="fa fa-arrow-left"></i> <?php echo t('back_to_themes'); ?>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    </div>
</div>
<?php } // End of if ($show_form && !empty($theme)) ?>

<?php include_once("footer.php"); ?>