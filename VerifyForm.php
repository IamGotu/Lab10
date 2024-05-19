
<?php
include('config/db_conn.php');
include('includes/header.php');
?>

<div class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 my-5">
                <div class="card my-5">
                    <div class="card-header bg-light">
                        <h2>Email Verification</h2>
                    </div>
                    <div class="card-body">

                        <!-- Your sign-up form goes here -->
                        <form action="verifyCode.php" method="POST" id="signupForm">

                            <div class="form-group">
                                <p>Please enter the verification code sent to your email:</p>
                            </div>

                            <div class="form-group">
                                <input type="text" name="Token" class="form-control" placeholder="Verification Code" required>
                                <input type="hidden" name="email" value="<?php echo isset($_GET['email']) ? $_GET['email'] : ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Verify</button>
                            </div>
                            
                        </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/script.php'); ?>
<?php include('includes/footer.php'); ?>