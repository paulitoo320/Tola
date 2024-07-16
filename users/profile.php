<?php require "../includes/header.php";?>
<?php require "../config/config.php";?>
<?php
    if (!isset($_SESSION['username'])){
        header("location: ".APPURL."");
    }

    // grapping the data

    if (isset($_GET['name'])){
        $name = $_GET['name'];
        $select = $conn->query("SELECT * FROM users WHERE username='$name'");
        $select->execute();

        $user = $select->fetch(PDO::FETCH_OBJ);

    }

    //number of topics for evry user
    $num_topics = $conn->query("SELECT COUNT(*) AS num_topics FROM topics WHERE user_name='$name'");
    $num_topics->execute();
    $all_num_topics = $num_topics->fetch(PDO::FETCH_OBJ);

    //number of replies for evry user
    $num_replies = $conn->query("SELECT COUNT(*) AS num_replies FROM replies WHERE user_name='$name'");
    $num_replies->execute();
    $all_num_replies = $num_replies->fetch(PDO::FETCH_OBJ);

?>

<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="main-col">
                <div class="block">
                    <h1 class="pull-left"><?php echo $user->name; ?></h1>
                    <div class="clearfix"></div>
                    <hr>
                    <ul id="topics">
                            <li id="main-topic" class="topic topic">
                                <div class="row">
                                    <div class="col-md-2">
                                        <img class="avatar pull-left" src="../img/<?php echo $user->avatar;?>" />
                                        <ul>
                                            <li><strong><?php echo $user->username; ?></strong></li>
                                            <li><a href="profile.php?name=<?php echo $user->username; ?>">Profile</a></li>
                                        </ul>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="topic-content pull-right">
                                            <p>
                                                <?php echo $user->about; ?>
                                            </p>
                                        </div>
                                        <a class="btn btn-success" href="" role="button">number of posts : <?php echo $all_num_topics->num_topics; ?></a>
                                        <a class="btn btn-primary" href="" role="button">number of replies : <?php echo $all_num_replies->num_replies; ?></a>
                                    </div>
                                </div>
                            </li>
                    </ul>
                </div>
            </div>
        </div>
<?php require "../includes/footer.php"; ?>