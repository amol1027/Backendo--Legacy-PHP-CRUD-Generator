<?php
// Get auth data from session if available
$auth_enabled = isset($_SESSION['auth']['enabled']) ? $_SESSION['auth']['enabled'] : false;
$auth_roles = isset($_SESSION['auth']['roles']) ? $_SESSION['auth']['roles'] : ['admin'];
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Step 3: Authentication Options</h4>
            </div>
            <div class="card-body">
                <form action="index.php?step=3" method="post">
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="add_login" name="add_login" <?php echo $auth_enabled ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="add_login">Add Login System</label>
                        </div>
                        <div class="form-text">Creates a session-based authentication system with login/logout functionality.</div>
                    </div>
                    
                    <div id="auth-options" class="<?php echo $auth_enabled ? '' : 'd-none'; ?>">
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">User Roles</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="roles[]" value="admin" id="role_admin" <?php echo in_array('admin', $auth_roles) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="role_admin">
                                            <strong>Admin</strong> - Full access to all features
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="roles[]" value="user" id="role_user" <?php echo in_array('user', $auth_roles) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="role_user">
                                            <strong>User</strong> - Limited access (view and edit own data)
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="roles[]" value="guest" id="role_guest" <?php echo in_array('guest', $auth_roles) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="role_guest">
                                            <strong>Guest</strong> - View-only access
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Authentication Features</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" name="auth_features[]" value="registration" id="feature_registration" <?php echo isset($_SESSION['auth']['features']) && in_array('registration', $_SESSION['auth']['features']) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="feature_registration">
                                                User Registration
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" name="auth_features[]" value="password_reset" id="feature_password_reset" <?php echo isset($_SESSION['auth']['features']) && in_array('password_reset', $_SESSION['auth']['features']) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="feature_password_reset">
                                                Password Reset
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" name="auth_features[]" value="remember_me" id="feature_remember_me" <?php echo isset($_SESSION['auth']['features']) && in_array('remember_me', $_SESSION['auth']['features']) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="feature_remember_me">
                                                Remember Me
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" name="auth_features[]" value="profile" id="feature_profile" <?php echo isset($_SESSION['auth']['features']) && in_array('profile', $_SESSION['auth']['features']) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="feature_profile">
                                                User Profile Page
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <a href="index.php?step=2" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Previous
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Next <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Toggle auth options visibility
    $(document).ready(function() {
        $('#add_login').change(function() {
            if ($(this).is(':checked')) {
                $('#auth-options').removeClass('d-none');
            } else {
                $('#auth-options').addClass('d-none');
            }
        });
    });
</script>