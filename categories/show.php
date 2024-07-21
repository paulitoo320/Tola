<?php require "../includes/header.php";?>
<?php require "../config/config.php";?>

<?php

    if (isset($_GET['name'])) {

        $name = $_GET['name'];

        $topicsPerPage = 10; // Number of topics per page
        $totalTopicsQuery = $conn->query("SELECT COUNT(*) AS all_topics FROM `topics` WHERE category = '$name'");
        $totalTopicsQuery->execute();
        $totalTopics = $totalTopicsQuery->fetch(PDO::FETCH_OBJ)->all_topics;
        $totalPages = ceil($totalTopics / $topicsPerPage);

// Get current page from URL, defaulting to 1
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $currentPage = max(1, min($currentPage, $totalPages)); // Ensure the current page is valid

// Calculate the starting topic for the current page
        $offset = ($currentPage - 1) * $topicsPerPage;

        $topics = $conn->query("SELECT * FROM topics WHERE category = '$name'");
        $topics->execute();
        $allTopics = $topics->fetchAll(PDO::FETCH_OBJ);
    }else{
        header("Location: ".APPURL."/404.php");
    }

?>

    <div class="container">
    <div class="row">
    <div class="col-md-8">
        <div class="main-col">
            <div class="block">
                <h1 class="pull-left">Category: <?php echo $name ?></h1>
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
                <ul id="topics">
                    <?php foreach ($allTopics as $topic) : ?>
                    <?php
                        $reply = $conn->query("SELECT COUNT(*) AS count_replies FROM `replies` WHERE replies.topic_id='$topic->id'");
                        $reply->execute();
                        $replies = $reply->fetch(PDO::FETCH_OBJ);
                    ?>
                        <li class="topics">
                            <div class="row"> <!-- un topic -->
                                <div class="col-md-8">
                                    <img class="avatar pull-left mr-3 rounded-circle" style="width: 40px; height: 40px;" src="../img/<?php echo $topic->user_image; ?>" />
                                    <a class="text-dark d-inline" href="<?php echo APPURL; ?>/users/profile.php?name=<?php echo $topic->user_name; ?>"><strong><?php echo $topic->user_name; ?></strong></a>
                                </div>
                                <div class="col-md-10" style="margin-left: 40px">
                                    Posted on: <?php echo $topic->created_at; ?>
                                    <div class="topic-content pull-right">
                                        <a class="text-dark" href="<?php echo APPURL ?>/topics/topic.php?id=<?php echo $topic->id; ?>">
                                            <div class="topic-description" id="description-<?php echo $topic->id; ?>">
                                                <?php echo substr($topic->body, 0, 100); ?>
                                            </div>
                                        </a>
                                    </div>
                                    <span class="color badge pull-right bg-light text-info">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat" viewBox="0 0 16 16">
                                            <path d="M2.678 11.894a1 1 0 0 1 .287.801 11 11 0 0 1-.398 2c1.395-.323 2.247-.697 2.634-.893a1 1 0 0 1 .71-.074A8 8 0 0 0 8 14c3.996 0 7-2.807 7-6s-3.004-6-7-6-7 2.808-7 6c0 1.468.617 2.83 1.678 3.894m-.493 3.905a22 22 0 0 1-.713.129c-.2.032-.352-.176-.273-.362a10 10 0 0 0 .244-.637l.003-.01c.248-.72.45-1.548.524-2.319C.743 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7-3.582 7-8 7a9 9 0 0 1-2.347-.306c-.52.263-1.639.742-3.468 1.105"/>
                                        </svg>
                                        <?php echo $replies->count_replies; ?>
                                    </span>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    </script>

    <script>
        function showMore(event, id) {
            event.preventDefault();
            var desc = document.getElementById('description-' + id);
            desc.innerHTML = '<?php echo addslashes($topic->body); ?>';
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
<?php require "../includes/footer.php"; ?>