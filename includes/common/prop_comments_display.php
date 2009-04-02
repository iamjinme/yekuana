<?php
    // running from system?
    if (empty($CFG) || empty($proposal)) {
        die;
    }
   
    $comments = get_records('prop_comments', 'id_propuesta', $proposal->id);

    if (empty($comments)) {
?>

<p class="error center"><?=__('Esta propuesta no tiene comentarios') ?></p>

<?php } else { ?>

<ul id="comments" class="narrow">

<?php foreach ($comments as $comment) { ?>

<li><em><?=$comment->login ?>:</em>

<?php $comment->body = nl2br(htmlspecialchars($comment->body, ENT_COMPAT, 'utf-8')); ?>

    <ul><li><?=$comment->body ?></li></ul>

<?php } ?>

</ul>

<?php } ?>
