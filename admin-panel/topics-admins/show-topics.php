<?php require "../layouts/header.php";?>
<?php require "../../config/config.php";?>
<?php
if (!isset($_SESSION['adminname'])){
    header("location: ".ADMINNAME."/admins/login-admins.php");
}
$topics = $conn->query("SELECT * FROM topics");
$topics->execute();
$allTopics = $topics->fetchAll(PDO::FETCH_OBJ);
//var_dump($allAdmins);
?>


<div class="row">
        <div class="col">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title mb-4 d-inline">Topics</h5>
            
              <table class="table mt-4">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">title</th>
                    <th scope="col">category</th>
                    <th scope="col">user</th>
                    <th scope="col">delete</th>
                  </tr>
                </thead>
                <tbody>
                <?php foreach ($allTopics as $topic) : ?>
                  <tr>
                    <th scope="row"><?php echo $topic->id; ?></th>
                    <td><?php echo $topic->title; ?></td>
                    <td><?php echo $topic->category; ?></td>
                    <td><?php echo $topic->user_name; ?></td>
                     <td><a href="delete-topic.php?id=<?php echo $topic->id; ?>" class="btn btn-danger  text-center ">delete</a></td>
                  </tr>
                <?php endforeach; ?>
                </tbody>
              </table> 
            </div>
          </div>
        </div>
      </div>

<?php require "../layouts/footer.php";?>
