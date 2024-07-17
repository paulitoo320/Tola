<?php
require "../includes/header.php";
require "../config/config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: " . APPURL . "/auth/login.php");
    exit();
}

if (isset($_GET['id']) && isset($_GET['vote_type']) && isset($_GET['topic_id'])) {
    $reply_id = $_GET['id'];
    $topic_id = $_GET['topic_id'];
    $vote_type = $_GET['vote_type'];
    $user_id = $_SESSION['user_id'];

    // Check if the user has already voted on this reply
    $checkVote = $conn->prepare("SELECT * FROM votesReplies WHERE user_id = :user_id AND reply_id = :reply_id");
    $checkVote->execute([
        ":user_id" => $user_id,
        ":reply_id" => $reply_id
    ]);

    if ($checkVote->rowCount() > 0) {
        // Update the existing vote
        $updateVote = $conn->prepare("UPDATE votesReplies SET vote_type = :vote_type WHERE user_id = :user_id AND reply_id = :reply_id");
        $updateVote->execute([
            ":vote_type" => $vote_type,
            ":user_id" => $user_id,
            ":reply_id" => $reply_id
        ]);
    } else {
        // Insert a new vote
        $insertVote = $conn->prepare("INSERT INTO votesReplies (user_id, reply_id, vote_type) VALUES (:user_id, :reply_id, :vote_type)");
        $insertVote->execute([
            ":user_id" => $user_id,
            ":reply_id" => $reply_id,
            ":vote_type" => $vote_type
        ]);
    }

    header("Location: " . APPURL . "/topics/topic.php?id=" . $topic_id);
    exit();
} else {
    header("Location: " . APPURL . "/404.php");
    exit();
}
?>
