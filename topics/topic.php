<?php require "../includes/header.php";?>
<?php require "../config/config.php";?>

<?php
if (isset($_GET['id'])){
    $id = $_GET['id'];

    $topic = $conn->query("SELECT * FROM topics WHERE id='$id'");
    $topic->execute();
    $singleTopic = $topic->fetch(PDO::FETCH_OBJ);

    // number of post for every user
    $topicCount = $conn->query("SELECT COUNT(*) AS count_topics FROM topics WHERE user_name='$singleTopic->user_name'");
    $topicCount->execute();
    $count = $topicCount->fetch(PDO::FETCH_OBJ);

    $replyCount = $conn->query("SELECT COUNT(*) AS count_replies FROM replies WHERE topic_id='$id'");
    $replyCount->execute();
    $countReplies = $replyCount->fetch(PDO::FETCH_OBJ);

    // grapping replies in a dynamic way
    $reply = $conn->query("SELECT * FROM replies WHERE topic_id='$id'");
    $reply->execute();
    $allreplies = $reply->fetchAll(PDO::FETCH_OBJ);
} else{
    header("Location: ".APPURL."/404.php");
}

    if (isset($_POST["submit"])) {
        if (empty($_POST['reply'])){
            echo "<script>alert('All fields must be filled');</script>";
        }else {
            $reply = $_POST['reply'];
            $user_id = $_SESSION['user_id'];
            $user_image = $_SESSION['user_image'];
            $topic_id = $id;
            $user_name = $_SESSION['username'];

            $insert = $conn->prepare("INSERT INTO replies (reply, user_id, user_image, topic_id, user_name) VALUES (:reply, :user_id, :user_image, :topic_id, :user_name)");
            $insert->execute([
                ":reply" => $reply,
                ":user_id" => $user_id,
                ":user_image" => $user_image,
                ":topic_id" => $topic_id,
                ":user_name" => $user_name,
            ]);
            header("location: " . APPURL . "/topics/topic.php?id=".$id."");
        }
    }
//votes Topic count
//votes up count
$voteUp = $conn->query("SELECT COUNT(*) AS count_votesUp FROM `votes` WHERE vote_type='up' AND topic_id='$id'");
$voteUp->execute();
$votesUp = $voteUp->fetch(PDO::FETCH_OBJ);

//votes down count
$voteDown = $conn->query("SELECT COUNT(*) AS count_votesDown FROM `votes` WHERE vote_type='down' AND topic_id='$id'");
$voteDown->execute();
$votesDown = $voteDown->fetch(PDO::FETCH_OBJ);

//votes Replies count
//votes up count
$voteReplyUp = null;
$votesRepliesUp = null;
$voteReplyUp = $conn->query("SELECT
    r.topic_id,
    r.id AS reply_id,
    COUNT(v.id) AS positive_votes_count
FROM
    replies r
LEFT JOIN
    votesReplies v ON r.id = v.reply_id AND v.vote_type = 'up'
GROUP BY
    r.topic_id, r.id;
");
$voteReplyUp->execute();
$votesRepliesUp = $voteReplyUp->fetchAll(PDO::FETCH_OBJ);

//votes down count
$voteReplyDown = null;
$votesRepliesDown = null;
$voteReplyDown = $conn->query("SELECT
    r.topic_id,
    r.id AS reply_id,
    COUNT(v.id) AS negative_votes_count
FROM
    replies r
LEFT JOIN
    votesReplies v ON r.id = v.reply_id AND v.vote_type = 'down'
GROUP BY
    r.topic_id, r.id;
");
$voteReplyDown->execute();
$votesRepliesDown = $voteReplyDown->fetchAll(PDO::FETCH_OBJ);
?>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="main-col">
                <div class="block">
                    <h1 class="pull-left"><?php echo $singleTopic->title; ?></h1>
                    <div class="clearfix"></div>
                    <hr>
                    <ul id="topics">
                        <li id="main-topic" class="topic topic">
                            <div class="row">
                                <div class="col-md-8">
                                    <img class="avatar pull-left mr-3 rounded-circle" style="width: 40px; height: 40px;" src="../img/<?php echo $singleTopic->user_image; ?>" />
                                    <a class="text-dark d-inline" href="<?php echo APPURL; ?>/users/profile.php?name=<?php echo $singleTopic->user_name; ?>"><strong><?php echo $singleTopic->user_name; ?></strong></a>
                                    <?php echo $count->count_topics; ?> Posts
                                    <a href="<?php echo APPURL;?>/users/profile.php?name=<?php echo $singleTopic->user_name; ?>">Profile</a>
                                </div>
                                <div class="col-md-10" style="margin-left: 40px">
                                    <div class="topic-content pull-right text-dark">
                                        <p><?php echo $singleTopic->body; ?></p>
                                    </div>
                                    <?php if (isset($_SESSION['username'])) : ?>
                                        <?php if ($singleTopic->user_name == $_SESSION['username']) : ?>
                                            <a class="btn btn-danger" href="delete.php?id=<?php echo $singleTopic->id;?>" role="button">Delete</a>
                                            <a class="btn btn-warning" href="update.php?id=<?php echo $singleTopic->id;?>" role="button">Update</a>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <button class="p-1 m-1 border rounded-pill bg-light">
                                        <a class="text-dark border-end" href="<?php echo APPURL; ?>/topics/vote.php?id=<?php echo $singleTopic->id; ?>&vote_type=up">
                                        <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 4 3 15h6v5h6v-5h6z" class="icon_svg-stroke icon_svg-fill" stroke-width="1.5" stroke="#666" fill="none" stroke-linejoin="round"></path></svg>
                                        Vote positif <?php echo  $votesUp->count_votesUp; ?>
                                        </a>
                                        <a class="text-dark border-start" href="<?php echo APPURL; ?>/topics/vote.php?id=<?php echo $singleTopic->id; ?>&vote_type=down">
                                            <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="m12 20 9-11h-6V4H9v5H3z" class="icon_svg-stroke icon_svg-fill" stroke="#666" fill="none" stroke-width="1.5" stroke-linejoin="round"></path></svg>
                                                <?php echo  $votesDown->count_votesDown; ?>
                                        </a>
                                    </button>
                                    <button class="m-2 text-dark p-1 bg-light border border-0">                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat" viewBox="0 0 16 16">
                                            <path d="M2.678 11.894a1 1 0 0 1 .287.801 11 11 0 0 1-.398 2c1.395-.323 2.247-.697 2.634-.893a1 1 0 0 1 .71-.074A8 8 0 0 0 8 14c3.996 0 7-2.807 7-6s-3.004-6-7-6-7 2.808-7 6c0 1.468.617 2.83 1.678 3.894m-.493 3.905a22 22 0 0 1-.713.129c-.2.032-.352-.176-.273-.362a10 10 0 0 0 .244-.637l.003-.01c.248-.72.45-1.548.524-2.319C.743 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7-3.582 7-8 7a9 9 0 0 1-2.347-.306c-.52.263-1.639.742-3.468 1.105"/>
                                        </svg>
                                        <?php echo $countReplies->count_replies; ?>
                                    </button>
                                </div>
                            </div>
                        </li>
                        <?php if (isset($_SESSION['username'])) : ?>
                            <form role="form" method="post" action="topic.php?id=<?php echo $id; ?>" class="d-flex align-items-center">
                                <textarea id="reply" rows="1" placeholder="comment on this content" class="form-control me-2" name="reply" style="width: 500px;"></textarea>
                                <button type="submit" name="submit" class="btn btn-default bg-info">Add a comment</button>
                                <script>
                                    CKEDITOR.replace('reply');
                                </script>
                            </form>
                        <?php endif; ?>
                        <?php foreach ($allreplies as $reply) : ?>
                            <li class="topic topic">
                                <div class="row">
                                    <div class="col-md-8">
                                        <img class="avatar pull-left mr-3 rounded-circle" style="width: 40px; height: 40px;" src="../img/<?php echo $reply->user_image; ?>" />
                                        <a class="text-dark d-inline" href="<?php echo APPURL; ?>/users/profile.php?name=<?php echo $reply->user_name; ?>"><strong><?php echo $reply->user_name; ?></strong></a>
                                        <a href="<?php echo APPURL;?>/users/profile.php?name=<?php echo $reply->user_name; ?>">Profile</a>
                                    </div>
                                    <div class="col-md-10" style="margin-left: 40px">
                                        <div class="topic-content pull-right">
                                            <p><?php echo $reply->reply;?></p>
                                        </div>
                                        <?php
                                        // Trouver le résultat correspondant à cette réponse
                                        $voteCountUp = null;
                                        foreach ($votesRepliesUp as $countUp) {
                                            if ($countUp->reply_id == $reply->id) {
                                                $voteCountUp = $countUp;
                                                break;
                                            }
                                        }
                                        $voteCountDown = null;
                                        foreach ($votesRepliesDown as $countDown) {
                                            if ($countDown->reply_id == $reply->id) {
                                                $voteCountDown = $countDown;
                                                break;
                                            }
                                        }
                                        ?>

                                        <a class="text-dark" href="<?php echo APPURL; ?>/topics/voteReply.php?id=<?php echo $reply->id; ?>&vote_type=up&topic_id=<?php echo $singleTopic->id;?>">
                                            <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 4 3 15h6v5h6v-5h6z" class="icon_svg-stroke icon_svg-fill" stroke-width="1.5" stroke="#666" fill="none" stroke-linejoin="round"></path></svg>
                                            Vote positif <?php echo  $voteCountUp->positive_votes_count; ?>
                                        </a>

                                        <a class="text-dark" href="<?php echo APPURL; ?>/topics/voteReply.php?id=<?php echo $reply->id; ?>&vote_type=down&topic_id=<?php echo $singleTopic->id;?>">
                                            <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="m12 20 9-11h-6V4H9v5H3z" class="icon_svg-stroke icon_svg-fill" stroke="#666" fill="none" stroke-width="1.5" stroke-linejoin="round"></path></svg>
                                            <?php echo  $voteCountDown->negative_votes_count; ?>
                                        </a>

                                        <?php if (isset($_SESSION['username'])) : ?>
                                            <?php if ($reply->user_id == $_SESSION['user_id']) : ?>
                                                <a class="btn btn-warning float-end" href="../replies/update.php?id=<?php echo $reply->id;?>" role="button">Update</a>
                                                <a class="btn btn-danger float-end" href="../replies/delete.php?id=<?php echo $reply->id;?>" role="button">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                                        <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                                    </svg>
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                </div>
            </div>
        </div>
        <?php require "../includes/footer.php";?>
