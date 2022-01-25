<?php

$jsIncludes[] = "includes/js/signin.js";
$cssIncludes[] = "includes/css/signin.css";

?>

<div class="card" style="max-width: 400px;">
    <div class="card-body">
        <h5 class="card-title">Welcome to G.O.!</h5>
        <p class="card-text">
            G.O. is a social media platform that connects you to other users in your geolocation.
            Make posts and interact with posts made in your current area, and see what is happening
            in the world around you.
        </p>
        <div class="d-grid">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-signup">Sign Up</a>
        </div>
        <p class="card-text text-center"><small>Already have an account? <a href="#" onclick="return false;" data-bs-toggle="modal" data-bs-target="#modal-signin">Sign in</a></small></p>
    </div>
</div>

<div class="modal fade" id="modal-signup" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create your account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/index.php" id="form-signup">
                    <input type="hidden" name="action" value="create_account">
                    <input type="hidden" name="no_template" value="1">
                    <div class="form-floating mb-3">
                        <input type="text" name="name" id="name" class="form-control" placeholder="John Doe" required>
                        <label for="name">Name</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="email" name="email" id="new_email" class="form-control" placeholder="johndoe@example.com" required>
                        <label for="new_email">Email</label>
                    </div>
                    <div class="mb-3">
                        <label for="birth_month" class="form-label mb-0">Username</label>
                        <p class="form-text" style="margin-bottom: 10px;">Cannot contain whitespace or special characters.</p>
                        <div class="input-group">
                            <span class="input-group-text">@</span>
                            <input type="text" name="handle" id="handle" class="form-control" style="height: 58px;" required>
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" name="password" id="new_password" class="form-control" placeholder="Password" required>
                        <label for="new_password">Password</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Password" required>
                        <label for="confirm_password">Confirm password</label>
                    </div>
                    <label for="birth_month" class="form-label mb-0">Date of birth</label>
                    <p class="form-text" style="margin-bottom: 10px;">Confirm your age (not shown publicly).</p>
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <div class="form-floating">
                                <select name="birth_month" id="birth_month" class="form-select" class="form-control" placeholder="Month" required>
                                    <option value=""></option>
                                    <option value="1">January</option>
                                    <option value="2">February</option>
                                    <option value="3">March</option>
                                    <option value="4">April</option>
                                    <option value="5">May</option>
                                    <option value="6">June</option>
                                    <option value="7">July</option>
                                    <option value="8">August</option>
                                    <option value="9">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                                </select>
                                <label for="birth_month">Month</label>
                            </div>
                        </div>
                        <div class="col-sm-3 mb-3">
                            <div class="form-floating">
                                <select name="birth_day" id="birth_day" class="form-select" class="form-control" placeholder="Day" required>
                                    <option value=""></option>
                                    <?php for ($i = 1; $i < 31; ++$i) { ?>
                                    <option value="<?= $i ?>"><?= $i ?></option>
                                    <?php } ?>
                                </select>
                                <label for="birth_day">Day</label>
                            </div>
                        </div>
                        <div class="col-sm-3 mb-3">
                            <div class="form-floating">
                                <select name="birth_year" id="birth_year" class="form-select" class="form-control" placeholder="Year" required>
                                    <option value=""></option>
                                    <?php for ($i = (int)date('Y'); $i >= 1950; --$i) { ?>
                                    <option value="<?= $i ?>"><?= $i ?></option>
                                    <?php } ?>
                                </select>
                                <label for="birth_year">Year</label>
                            </div>
                        </div>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Continue</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-signin" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sign In</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/index.php" id="form-signin">
                    <input type="hidden" name="action" value="signin_process">
                    <input type="hidden" name="no_template" value="1">
                    <div class="form-floating mb-3">
                        <input type="text" name="user" id="user" class="form-control" placeholder="johndoe@example.com" required>
                        <label for="user">Email or @username</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                        <label for="password">Password</label>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Continue</a>
                    </div>
                    <p class="form-text text-center mb-0">Don't have an account? <a href="#" onclick="return false;">Register</a></p>
                </form>
            </div>
        </div>
    </div>
</div>