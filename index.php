<?php require "includes/header.php";?>
<?php require "config/config.php";?>

<?php
$topics = $conn->query("
    SELECT topics.id AS id,
           topics.title AS title,
           topics.category AS category,
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
    GROUP BY topics.id, topics.title, topics.category, topics.user_name, topics.user_image, topics.created_at
    ORDER BY total_votes DESC, created_at DESC
");

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
                    <ul id="topics">
                        <?php foreach ($allTopics as $topic) : ?>
                            <li class="topic">
                                <div class="row">
                                    <div class="col-md-2">
                                        <img class="avatar pull-left" src="img/<?php echo $topic->user_image;?>" />
                                    </div>
                                    <div class="col-md-10">
                                        <div class="topic-content pull-right">
                                            <h3><a href="topics/topic.php?id=<?php echo $topic->id;?>"><?php echo $topic->title;?></a></h3>
                                            <div class="topic-info">
                                                <a href="<?php echo APPURL; ?>/categories/show.php?name=<?php echo $topic->category; ?>"><?php echo $topic->category;?></a> >> <a href="<?php echo APPURL; ?>/users/profile.php?name=<?php echo $topic->user_name; ?>"><?php echo $topic->user_name;?></a> >> Posted on: <?php echo $topic->created_at;?>
                                                <span class="color badge pull-right">number of responses: <?php echo $topic->count_replies;?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <?php require "includes/footer.php"; ?>
