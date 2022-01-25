<?php

$jsIncludes[] = "includes/js/profile.js";
$cssIncludes[] = "includes/css/profile.css";

$account = $GLOBALS['user'];
if (array_key_exists('handle', $_REQUEST) && !empty($_REQUEST['handle'])) try {
    // Attempt to load someone else's account
    $account = new Account($_REQUEST['handle']);
} catch (Exception $e) {}
$isMyAccount = ($GLOBALS['user']->getHandle() === $account->getHandle());

$photoURL = "/?action=account&subaction=get_photo&handle=".$account->getHandle()."&no_template=1";

?>

<div class="card" id="profile-container" style="max-width: 600px;">
    <img src="includes/images/placeholder.jpg" class="card-img-top" alt="cover photo">
    <div class="card-body position-relative">
        <button type="button" data-bs-toggle="modal" data-bs-target="#modal-profile-picture" id="change-photo-btn">
            <img src="<?= $photoURL ?>" alt="<?= $account->getName() ?>">
            <div class="overlay">
                <i class="fas fa-camera"></i>
            </div>
        </button>
        <div class="text-end">
            <?php if ($isMyAccount) { ?>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-edit-profile">Edit profile</button>
            <?php } else { ?>
                <button class="btn btn-primary">Follow</button>
            <?php } ?>
        </div>
        <h4 class="card-title mb-0"><?= $account->getName() ?></h4>
        <p class="mb-2 text-muted">@<?= $account->getHandle() ?></p>
        <p class="card-text"><?= $account->getBio() ?></p>
        <div class="factoids">
            <span class="text-muted"><i class="fas fa-birthday-cake"></i> Born <?= $account->getBirthday("F j, Y") ?></span>
            <span class="text-muted"><i class="far fa-calendar-alt"></i> Joined <?= $account->getDateCreated("F Y") ?></span>
        </div>
        <ul class="nav nav-pills nav-fill mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Posts</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Replies</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Events</button>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">...</div>
            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">...</div>
            <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">...</div>
        </div>
    </div>
</div>

<?php if ($isMyAccount) { ?>
<div class="modal fade" id="modal-edit-profile" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/index.php" id="form-edit-profile">
                    <input type="hidden" name="action" value="account">
                    <input type="hidden" name="subaction" value="edit_process">
                    <input type="hidden" name="no_template" value="1">
                    <div class="form-floating mb-3">
                        <input type="text" name="name" id="name" class="form-control" placeholder="John Doe" value="<?= $account->getName() ?>" required>
                        <label for="name">Name</label>
                    </div>
                    <div class="form-floating mb-3">
                        <textarea class="form-control" name="bio" id="bio" placeholder="Tell us about yourself." style="height: 100px;"><?= $account->getBio() ?></textarea>
                        <label for="bio">Bio</label>
                    </div>
                    <label for="birth_month" class="form-label mb-0">Date of birth</label>
                    <p class="form-text" style="margin-bottom: 10px;">Not shown publicly</p>
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <div class="form-floating">
                                <select name="birth_month" id="birth_month" class="form-select" class="form-control" placeholder="Month" required>
                                    <option value=""></option>
                                    <option value="1"<?= ($account->getBirthday("n") === "1" ? " selected" : "") ?>>January</option>
                                    <option value="2"<?= ($account->getBirthday("n") === "2" ? " selected" : "") ?>>February</option>
                                    <option value="3"<?= ($account->getBirthday("n") === "3" ? " selected" : "") ?>>March</option>
                                    <option value="4"<?= ($account->getBirthday("n") === "4" ? " selected" : "") ?>>April</option>
                                    <option value="5"<?= ($account->getBirthday("n") === "5" ? " selected" : "") ?>>May</option>
                                    <option value="6"<?= ($account->getBirthday("n") === "6" ? " selected" : "") ?>>June</option>
                                    <option value="7"<?= ($account->getBirthday("n") === "7" ? " selected" : "") ?>>July</option>
                                    <option value="8"<?= ($account->getBirthday("n") === "8" ? " selected" : "") ?>>August</option>
                                    <option value="9"<?= ($account->getBirthday("n") === "9" ? " selected" : "") ?>>September</option>
                                    <option value="10"<?= ($account->getBirthday("n") === "10" ? " selected" : "") ?>>October</option>
                                    <option value="11"<?= ($account->getBirthday("n") === "11" ? " selected" : "") ?>>November</option>
                                    <option value="12"<?= ($account->getBirthday("n") === "12" ? " selected" : "") ?>>December</option>
                                </select>
                                <label for="birth_month">Month</label>
                            </div>
                        </div>
                        <div class="col-sm-3 mb-3">
                            <div class="form-floating">
                                <select name="birth_day" id="birth_day" class="form-select" class="form-control" placeholder="Day" required>
                                    <option value=""></option>
                                    <?php for ($i = 1; $i < 31; ++$i) { ?>
                                    <option value="<?= $i ?>"<?= ($account->getBirthday("j") == $i ? " selected" : "") ?>><?= $i ?></option>
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
                                    <option value="<?= $i ?>"<?= ($account->getBirthday("Y") == $i ? " selected" : "") ?>><?= $i ?></option>
                                    <?php } ?>
                                </select>
                                <label for="birth_year">Year</label>
                            </div>
                        </div>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Save changes</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<div class="modal fade" id="modal-profile-picture" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Profile picture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php if ($isMyAccount) { ?>
                <form>
                    <div class="mb-3">
                        <input type="file" name="image" id="image" class="form-control" onchange="readURL(this);">
                    </div>
                    <div class="mb-3">
                        <img id="blah" src="<?= $photoURL ?>" class="img-fluid" alt="your image">
                    </div>
                    <div id="cropped_result"></div>
                    <div class="d-grid">
                        <button type="button" class="btn btn-primary" id="crop_button">Save</a>
                    </div>
                </form>
                <?php } else { ?>
                    <img src="<?= $photoURL ?>" class="img-fluid" alt="your image">
                <?php } ?>
            </div>
        </div>
    </div>
</div>
