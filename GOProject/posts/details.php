<?php

require_once("classes/Post.php");

$post = new Post($_REQUEST['post_id']);
$creator = new Account($post->getHandle());

?>

<div class="post" data-post-id="<?= $post->getID() ?>">
    <a href="/?action=account&handle=<?= $creator->getHandle() ?>" class="mb-1 post-header">
        <img class="user-img" src="/?action=account&subaction=get_photo&handle=<?= $creator->getHandle() ?>&no_template=1" alt="<?= $creator->getName() ?>">
        <div class="user-data">
            <p class="user-name"><?= $creator->getName() ?></p>
            <p class="user-handle">@<?= $creator->getHandle() ?></p>
        </div>
    </a>
    <p class="post-content mb-1"><?= $post->getContent() ?></p>
    <p class="mb-3"><?= $post->getDateCreated('g:i A') ?> &middot; <?= $post->getDateCreated('F jS, Y') ?></p>
    <div class="post-actions">
        <button type="button" class="post-reply">
            <i class="fas fa-comment"></i>
            0
        </button>
        <button type="button" class="post-like<?= ($GLOBALS['user']->likedPost($post->getID()) ? " liked" : "") ?>" onclick="likePost(<?= $post->getID() ?>)">
            <i class="fas fa-heart"></i>
            <span><?= $post->getNumLikes() ?></span>
        </button>
    </div>
</div>