<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" integrity="sha512-0SPWAwpC/17yYyZ/4HSllgaK7/gg9OlVozq8K7rf3J8LvCjYEEIfzzpnA2/SSjpGIunCSD18r3UhvDcu/xncWA==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <script src="https://kit.fontawesome.com/6d03f835c8.js" crossorigin="anonymous"></script>
    <link href="includes/css/global.css" rel="stylesheet">
    <?php foreach ($cssIncludes as $include) { ?>
    <link href="<?= $include ?>" rel="stylesheet">
    <?php } ?>
    <title>G.O.</title>
</head>
<body>

<div class="toast-container position-absolute top-0 end-0 p-3" style="z-index:10000;"></div>

<?php if ($GLOBALS['user']) { ?>
<nav id="top-nav">
    <div>
        <a href="/" class="circle-btn"><i class="fas fa-map"></i></a>
        <button type="button" class="circle-btn"><i class="fas fa-search"></i></button>
    </div>
    <div>
        <button type="button" class="top-nav-menu-toggle" data-target="#user-menu" id="btn-user-menu">
            <img class="user-img" src="/?action=account&subaction=get_photo&handle=<?= $GLOBALS['user']->getHandle() ?>&no_template=1">
            <span><?= $GLOBALS['user']->getName() ?></span>
        </button>
        <button type="button" class="circle-btn"><i class="fas fa-comment"></i></button>
        <button type="button" class="circle-btn"><i class="fas fa-bell"></i></button>
    </div>
</nav>
<div id="user-menu" class="top-nav-menu" style="display: none;">
    <ul>
        <li>
            <a href="/?action=account">
                <div>
                    <img class="user-img" src="/?action=account&subaction=get_photo&handle=<?= $GLOBALS['user']->getHandle() ?>&no_template=1">
                    <div class="menu-item-info">
                        <p><?= $GLOBALS['user']->getName() ?></p>
                        <p>See your profile</p>
                    </div>
                </div>
            </a>
        </li>
        <li class="separator"></li>
        <li>
            <a href="/?action=account&subaction=signout">
                <div>
                    <i class="fas fa-sign-out-alt"></i>
                    <div class="menu-item-info">
                        <p>Log Out</p>
                    </div>
                </div>
                <i class="fas fa-chevron-right"></i>
            </a>
        </li>
    </ul>
</div>
<?php } ?>

<div id="content-wrapper">
    <?= $bodyContent ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js" integrity="sha512-ooSWpxJsiXe6t4+PPjCgYmVfr1NS5QXJACcR/FPpsdm6kqG1FmQ2SVyg2RXeVuCRBLr0lWHnWJP6Zs1Efvxzww==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script type="text/javascript" src="includes/js/global.js"></script>
<?php foreach ($jsIncludes as $include) { ?>
<script type="text/javascript" src="<?= $include ?>"></script>
<?php } ?>
<?php foreach ($jsAsyncIncludes as $include) { ?>
<script type="text/javascript" async src="<?= $include ?>"></script>
<?php } ?>

</body>
</html>
