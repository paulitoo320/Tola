<?php
require "includes/header.php";
require "config/config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: " . APPURL . "/auth/login.php");
    exit();
}

if (isset($_GET['id']) && isset($_GET['vote_type'])) {
    $topic_id = $_GET['id'];
    $vote_type = $_GET['vote_type'];
    $user_id = $_SESSION['user_id'];

    // Check if the user has already voted on this topic
    $checkVote = $conn->prepare("SELECT * FROM votes WHERE user_id = :user_id AND topic_id = :topic_id");
    $checkVote->execute([
        ":user_id" => $user_id,
        ":topic_id" => $topic_id
    ]);

    if ($checkVote->rowCount() > 0) {
        // Update the existing vote
        $updateVote = $conn->prepare("UPDATE votes SET vote_type = :vote_type WHERE user_id = :user_id AND topic_id = :topic_id");
        $updateVote->execute([
            ":vote_type" => $vote_type,
            ":user_id" => $user_id,
            ":topic_id" => $topic_id
        ]);
    } else {
        // Insert a new vote
        $insertVote = $conn->prepare("INSERT INTO votes (user_id, topic_id, vote_type) VALUES (:user_id, :topic_id, :vote_type)");
        $insertVote->execute([
            ":user_id" => $user_id,
            ":topic_id" => $topic_id,
            ":vote_type" => $vote_type
        ]);
    }

    header("Location: " . APPURL . "");
    exit();
} else {
    header("Location: " . APPURL . "/404.php");
    exit();
}
?>
