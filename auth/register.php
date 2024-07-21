<?php require "../includes/header.php";?>
<?php require "../config/config.php"; ?>

<?php

if (isset($_POST['submit'])) {
    if (empty($_POST['name']) or empty($_POST['email']) or empty($_POST['username']) or empty($_POST['password']) or empty($_POST['about'])) {
        echo "<script>alert('All fields must be filled');</script>";
    } else {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $about = $_POST['about'];
        $avatar = $_FILES['avatar']['name'];

        $dir = "img/" . basename($avatar);

        $insert = $conn->prepare("INSERT INTO users (name, email, username, password, about, avatar) VALUES (:name, :email, :username, :password, :about, :avatar)");

        $insert->execute([
            ":name" => $name,
            ":email" => $email,
            ":username" => $username,
            ":password" => $password,
            ":about" => $about,
            ":avatar" => $avatar,
        ]);

        header("location: login.php");
    }
}

// grapping categories
$categories_select = $conn->prepare("SELECT * FROM categories");
$categories_select->execute();
$allCats = $categories_select->fetchAll(PDO::FETCH_OBJ);


?>
<div class="container" >
<div class="row">
        <div class="col-md-8">
            <div class="main-col">
                <div class="block">

                    <h1 class="pull-left">Register</h1>
                    <div class="clearfix"></div>
                    <hr>

                    <form role="form" enctype="multipart/form-data" method="post" action="register.php">
                        <div class="form-group">
                            <label>Name*</label> <input type="text" class="form-control" name="name" placeholder="Enter Your Name">
                        </div>
                        <div class="form-group">
                            <label>Email Address*</label> <input type="email" class="form-control" name="email" placeholder="Enter Your Email Address">
                        </div>
                        <div class="form-group">
                            <label>Choose Username*</label> <input type="text" class="form-control" name="username" placeholder="Create A Username">
                        </div>


                        <div>
                            <!-- TO-DO en s’inscrivant, les utilisateurs sélectionnent les thèmes sur lesquels ils ont des connaissances. -->
                            <label>Choose Your Interests*</label><br>
                            <!-- Button trigger modal -->
                            <button style="background-color: #f4f4f4; border-color:#dee2e6;" type="button" id="categoryModalButton" class="btn boutonne" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                Choose
                            </button>

                            <!-- #f4f4f4 (clair) Modal  #dee2e6(fonce)-->
                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <?php foreach ($allCats as $cat) : ?>

                                                    <input type="checkbox" class="btn-check image" id="btncheck<?php echo $cat->id; ?>" name="category[]" value="<?php echo $cat->name; ?>" autocomplete="off">
                                                    <label class="btn btn-outline-primary category-label" for="btncheck<?php echo $cat->id; ?>" style="background-image: url('../img/<?php echo $cat->name; ?>.webp');"><?php echo $cat->name; ?></label>

                                                    <!-- <input type="checkbox" class="btn-check" id="btncheck<!?php echo $cat->id; ?>" name="category[]" value="<!?php echo $cat->name; ?>" autocomplete="off">
                                                    <label class="btn btn-outline-primary " for="btncheck<!?php echo $cat->id; ?>"><!?php echo $cat->name; ?></label>
                                               -->
                                                <?php endforeach; ?>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Save changes</button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- <div class="modal-body">
                                            <div class="row">
                                                <!?php foreach ($allCats as $cat) : ?>
                                                    <input type="checkbox" class="btn-check" id="btncheck<!?php echo $cat->id; ?>" name="category[]" value="<!?php echo $cat->name; ?>" autocomplete="off">
                                                    <label class="btn btn-outline-primary " for="btncheck<!?php echo $cat->id; ?>"><!?php echo $cat->name; ?></label>
                                                <!?php endforeach; ?>
                                            </div>
                                        </div> -->
                        <div class="form-group">
                            <label>Password*</label> <input type="password" class="form-control" name="password" placeholder="Enter A Password">
                        </div>
                        <div class="form-group">
                            <label>Upload Avatar</label><br>
                            <div class="mb-3">
                                <input class="form-control" type="file" id="formFile" name="avatar">
                            </div>

                            <p class="help-block"></p>
                        </div>
                        <div class="form-group">
                            <label>About Me</label>
                            <textarea id="about" rows="6" cols="80" class="form-control" name="about" placeholder="Tell us about yourself (Optional)"></textarea>
                        </div>
                        <input data-loading-text="Loading..." name="submit" type="submit" class="color btn btn-default bg-info" value="Register" />
                    </form>
                </div>
            </div>
        </div>


<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

<script>
    function updateSelectedCategories() {
        const checkboxes = document.querySelectorAll('.btn-check');
        const selected = [];
        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                selected.push(checkbox.nextElementSibling.textContent);
            }
        });
        const selectedText = selected.length ? selected.join(', ') : 'Launch demo modal';
        document.getElementById('categoryModalButton').textContent = selectedText;
    }
</script>

<?php require "../includes/footer.php";?>
