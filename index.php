<?php require "includes/header.php"; ?>
<?php require "config/config.php"; ?>

<?php
$topicsPerPage = 10; // Number of topics per page
$totalTopicsQuery = $conn->query("SELECT COUNT(*) AS all_topics FROM `topics`");
$totalTopicsQuery->execute();
$totalTopics = $totalTopicsQuery->fetch(PDO::FETCH_OBJ)->all_topics;
$totalPages = ceil($totalTopics / $topicsPerPage);

// Get current page from URL, defaulting to 1
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$currentPage = max(1, min($currentPage, $totalPages)); // Ensure the current page is valid

// Calculate the starting topic for the current page
$offset = ($currentPage - 1) * $topicsPerPage;
$topics = $conn->query("
    SELECT topics.id AS id,
           topics.title AS title,
           topics.category AS category,
           topics.body AS body,
           topics.user_name AS user_name,
           topics.user_image AS user_image,
           topics.created_at AS created_at,
           COUNT(replies.topic_id) AS count_replies,
           SUM(IFNULL(votes.count_votesUp, 0)) AS total_votes
    FROM topics  
    LEFT JOIN replies ON topics.id = replies.topic_id
    LEFT JOIN (
        SELECT topic_id, COUNT(*) AS count_votesUp
        FROM votes
        WHERE vote_type = 'up'
        GROUP BY topic_id
    ) AS votes ON topics.id = votes.topic_id
    GROUP BY topics.id, topics.title, topics.category, topics.body, topics.user_name, topics.user_image, topics.created_at
    ORDER BY count_votesUp DESC
");


if (isset($_GET['id'])){
    $id = $_GET['id'];

    if (isset($_POST["submit"])) {
        if (!isset($_SESSION['user_id'])) {
            header("Location: " . APPURL . "/auth/login.php");
            exit();
        }else{
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
                header("location: " . APPURL . "");
            }
        }

    }
}

$topics->execute();
$allTopics = $topics->fetchAll(PDO::FETCH_OBJ);

?>
    <div class="container">
    <div class="row">
    <div class="col-md-8">
        <div class="main-col">
            <div class="block">
                <h1 class="pull-left">Welcome to forum</h1>
                <div class="clearfix"></div>
                <hr>
                <ul class="container uk-list uk-list-line" id="infinite-list"> <!--tous les topics-->
                </ul>
                <div id="scrollspy" data-uk-scrollspy="{topoffset: 200, repeat: true}">
                    <i class="uk-icon-refresh uk-icon-spin uk-hidden"></i>
                </div>
            </div>
        </div>
    </div>
    <script type="text/listtemplate">
        <?php foreach ($allTopics as $topic) : ?>
        <ul>
        <li class="topics">
            <div class="row"> <!-- un topic -->
                <div class="col-md-8">
                    <img class="avatar pull-left mr-3 rounded-circle" style="width: 40px; height: 40px;" src="img/<?php echo $topic->user_image; ?>" />
                    <a class="text-dark d-inline" href="<?php echo APPURL; ?>/users/profile.php?name=<?php echo $topic->user_name; ?>"><strong><?php echo $topic->user_name; ?></strong></a>
                </div>
                <div class="col-md-10" style="margin-left: 40px">
                    <a class="text-dark d-block" href="<?php echo APPURL; ?>/categories/show.php?name=<?php echo $topic->category; ?>">Category: <?php echo $topic->category; ?></a>
                    Posted on: <?php echo $topic->created_at; ?>
                    <div class="topic-content pull-right">
                        <a class="text-dark" href="topics/topic.php?id=<?php echo $topic->id; ?>">
                            <div class="topic-description" id="description-<?php echo $topic->id; ?>">
                                <?php echo substr($topic->body, 0, 100); ?>
                            </div>
                        </a>
                    </div>
                    <?php
                    //votes Topic count
                    //votes up count
                    $voteUp = $conn->query("SELECT COUNT(*) AS count_votesUp FROM `votes` WHERE vote_type='up' AND topic_id='$topic->id'");
                    $voteUp->execute();
                    $votesUp = $voteUp->fetch(PDO::FETCH_OBJ);

                    //votes down count
                    $voteDown = $conn->query("SELECT COUNT(*) AS count_votesDown FROM `votes` WHERE vote_type='down' AND topic_id='$topic->id'");
                    $voteDown->execute();
                    $votesDown = $voteDown->fetch(PDO::FETCH_OBJ);
                    ?>
                    <div class="p-1 m-1">
                        <button class="p-1 m-1 border rounded-pill bg-light" >
                            <a class="text-dark border-end" href="<?php echo APPURL; ?>/voteIndex.php?id=<?php echo $topic->id; ?>&vote_type=up">
                                <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 4 3 15h6v5h6v-5h6z" class="icon_svg-stroke icon_svg-fill" stroke-width="1.5" stroke="#666" fill="none" stroke-linejoin="round"></path></svg>
                                Vote positif <?php echo  $votesUp->count_votesUp; ?>
                            </a>
                            <a class="text-dark border-start" href="<?php echo APPURL; ?>/voteIndex.php?id=<?php echo $topic->id; ?>&vote_type=down">
                                <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="m12 20 9-11h-6V4H9v5H3z" class="icon_svg-stroke icon_svg-fill" stroke="#666" fill="none" stroke-width="1.5" stroke-linejoin="round"></path></svg>
                                <?php echo  $votesDown->count_votesDown; ?>
                            </a>
                        </button>

                        <button  onclick="showFormButton('<?php echo $topic->id; ?>')" class="m-2 text-dark p-1 bg-light comment-btn border border-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat" viewBox="0 0 16 16">
                                <path d="M2.678 11.894a1 1 0 0 1 .287.801 11 11 0 0 1-.398 2c1.395-.323 2.247-.697 2.634-.893a1 1 0 0 1 .71-.074A8 8 0 0 0 8 14c3.996 0 7-2.807 7-6s-3.004-6-7-6-7 2.808-7 6c0 1.468.617 2.83 1.678 3.894m-.493 3.905a22 22 0 0 1-.713.129c-.2.032-.352-.176-.273-.362a10 10 0 0 0 .244-.637l.003-.01c.248-.72.45-1.548.524-2.319C.743 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7-3.582 7-8 7a9 9 0 0 1-2.347-.306c-.52.263-1.639.742-3.468 1.105"/>
                            </svg>
                            <?php echo $topic->count_replies; ?>
                        </button>
                        <div id="myform-<?php echo $topic->id; ?>" style="display: none;">
                            <form role="form" method="post" action="index.php?id=<?php echo $topic->id; ?>" class="d-flex align-items-center mb-3">
                                <textarea id="reply-<?php echo $topic->id; ?>" rows="1" placeholder="comment on this content" class="form-control me-1" name="reply" style="width: 300px;"></textarea>
                                <button type="submit" name="submit" class="btn btn-default bg-info">Add a comment</button>
                            </form>

                            <?php
                            // grapping replies in a dynamic way
                            $reply = $conn->query("SELECT * FROM replies WHERE topic_id='$topic->id'");
                            $reply->execute();
                            $allreplies = $reply->fetchAll(PDO::FETCH_OBJ);
                            ?>
                            <ul class="list-unstyled">
                                <?php foreach ($allreplies as $reply) : ?>
                                    <li class="topic topic mb-3">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <img class="avatar pull-left mr-3 rounded-circle" style="width: 40px; height: 40px;" src="img/<?php echo $reply->user_image; ?>"  alt=""/>
                                            </div>
                                            <div class="col-md-10">
                                                <a class="text-dark d-inline" href="<?php echo APPURL; ?>/users/profile.php?name=<?php echo $reply->user_name; ?>"><strong><?php echo $reply->user_name; ?></strong></a>
                                                <a href="<?php echo APPURL; ?>/users/profile.php?name=<?php echo $reply->user_name; ?>">Profile</a>
                                                <div class="topic-content">
                                                    <p><?php echo $reply->reply; ?></p>
                                                </div>
                                                <?php

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
                                                <a class="text-dark me-2" href="<?php echo APPURL; ?>/voteRepliesIndex.php?id=<?php echo $reply->id; ?>&vote_type=up&topic_id=<?php echo $topic->id;?>">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 4 3 15h6v5h6v-5h6z" class="icon_svg-stroke icon_svg-fill" stroke-width="1.5" stroke="#666" fill="none" stroke-linejoin="round"></path></svg>
                                                    Vote positif <?php echo $voteCountUp->positive_votes_count; ?>
                                                </a>
                                                <a class="text-dark me-2" href="<?php echo APPURL; ?>/voteRepliesIndex.php?id=<?php echo $reply->id; ?>&vote_type=down&topic_id=<?php echo $topic->id;?>">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="m12 20 9-11h-6V4H9v5H3z" class="icon_svg-stroke icon_svg-fill" stroke="#666" fill="none" stroke-width="1.5" stroke-linejoin="round"></path></svg>
                                                    <?php echo $voteCountDown->negative_votes_count; ?>
                                                </a>
                                                <?php if (isset($_SESSION['username'])) : ?>
                                                    <?php if ($reply->user_id == $_SESSION['user_id']) : ?>
                                                        <a class="btn btn-warning float-end me-2" href="replies/update.php?id=<?php echo $reply->id;?>" role="button" >
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-repeat m-1" viewBox="0 0 16 16">
                                                                <path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41m-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9"/>
                                                                <path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5 5 0 0 0 8 3M3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9z"/>
                                                            </svg>
                                                        </a>
                                                        <a class="btn btn-danger float-end" href="replies/delete.php?id=<?php echo $reply->id;?>" role="button">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash m-1" viewBox="0 0 16 16">
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
            </div>
        </li>
        </ul>
    <?php endforeach; ?>
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function(){
            $(".comment-btn").click(function(){
                $(this).closest(".topic").find(".replies-list").toggle();
            });
        });
    </script>
    <script>
        function showMore(event, id) {
            event.preventDefault();
            var desc = document.getElementById('description-' + id);
            desc.innerHTML = '<?php echo addslashes($topic->body); ?> ';
        }
    </script>
        <script>
            function showFormButton(topicId) {
                var formId = "myform-" + topicId;
                var form = document.getElementById(formId);
                if (form.style.display === "none" || form.style.display === "") {
                    form.style.display = "block";
                } else {
                    form.style.display = "none";
                }
            }
        </script>

    <style>
        .topic-description {
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
            display: inline-block;
            max-width: 100%;
            color: #000;
        }
        .topic-description.full {
            white-space: normal;
            text-overflow: clip;
        }
    </style>

<?php require "includes/footer.php"; ?>

