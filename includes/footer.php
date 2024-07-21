<?php

$topics = $conn->query("SELECT COUNT(*) AS all_topics FROM `topics`");
$topics->execute();
$allTopics = $topics->fetch(PDO::FETCH_OBJ);

//number of posts inside every category
$categories = $conn->query("SELECT categories.id AS id, categories.name AS name,
    COUNT(topics.category) AS count_category 
    FROM categories 
    LEFT JOIN topics ON categories.name = topics.category
    GROUP BY categories.id, categories.name 
    LIMIT 0, 25;");

$categories->execute();

$allCategories = $categories->fetchAll(PDO::FETCH_OBJ);

//forum stats

//users count
$users = $conn->query("SELECT COUNT(*) AS count_users FROM `users`");
$users->execute();
$allUsers = $users->fetch(PDO::FETCH_OBJ);

//count topics
$topics = $conn->query("SELECT COUNT(*) AS count_topics FROM `topics`");
$topics->execute();
$countTopics = $topics->fetch(PDO::FETCH_OBJ);

//count categories
$categories_count = $conn->query("SELECT COUNT(*) AS categories_count FROM `topics`");
$categories_count->execute();
$allCategories_count = $categories_count->fetch(PDO::FETCH_OBJ);

?>


<div class="col-md-4">
    <div class="sidebar">


        <div class="block">
            <h3>Categories</h3>
            <div class="list-group block ">
                <a href="#" class="list-group-item active bg-info">All Topics <span class="badge pull-right bg-light float-end text-info"><?php echo $allTopics->all_topics; ?></span></a>
                <?php foreach ($allCategories as $category) : ?>
                    <a href="<?php echo APPURL; ?>/categories/show.php?name=<?php echo $category->name; ?>" class="list-group-item"><?php echo $category->name ?><span class="color badge pull-right bg-info float-end"><?php echo $category->count_category ?></span></a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="block" style="margin-top: 20px;">
            <h3 class="margin-top: 40px">Forum Statistics</h3>
            <div class="list-group">
                <a href="#" class="list-group-item">Total Number of Users:<span class="color badge pull-right bg-info float-end"><?php echo  $allUsers->count_users; ?></span></a>
                <a href="#" class="list-group-item">Total Number of Topics:<span class="color badge pull-right bg-info float-end"><?php echo  $countTopics->count_topics; ?></span></a>
                <a href="#" class="list-group-item">Total Number of Categories: <span class="color badge pull-right bg-info float-end"><?php echo  $allCategories_count->categories_count; ?></span></a>
            </div>
        </div>
    </div>
</div>
</div>


</div>
</div>
</div><!-- /.container -->


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

<script src="<?php echo APPURL; ?>/js/bootstrap.js"></script>


<!--
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.27.2/js/uikit.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.27.2/js/components/lightbox.min.js"></script>
<script>
    (function($, UI) {
        var list = $('#infinite-list'),
            scrollspy = $('#scrollspy'),
            spinner = $('#scrollspy i'),
            content = $('script[type="text/listtemplate"]').html(),
            addContent = function() {
                spinner.removeClass('uk-hidden');
                //use ajax-request to retreive content, timeout for simulating callback
                setTimeout(function() {
                    //add to dom
                    list.append(content);
                    spinner.addClass('uk-hidden');
                    //when scrollspy is not pushed down far enough to get out of view, add more content
                    setTimeout(function() {
                        if (UI.Utils.isInView(scrollspy, {
                            topoffset: 200
                        })) {
                            addContent();
                        }
                    }, 50);
                }, 500)
            }

        UI.ready(function() {
            //add content when inview triggers, 200px before end of list comes into view
            scrollspy.on('inview.uk.scrollspy', function() {
                addContent();
            });
            //initial content
            addContent();
            //just to show the UIkit version in this pen
            // UI.$body.prepend('<div class="uk-float-right uk-badge">UIkit version: ' + UIkit.version + '</div>');
        });

    }(UIkit.$, UIkit));
</script>

</body>

</html>