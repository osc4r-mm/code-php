<?php 
session_start();
include('includes/header.php');
include('includes/session.php');

$errors = get_errors('errors');
$success = get_messages('success');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['page'])) {
    $_SESSION['current_page'] = $_POST['page'];
}
?>

<main class="container">
    <!-- Canvia l'include -->
    <?php 
    if (isset($_GET['page']) && $_GET['page'] === 'form_post') {
        include('includes/form_post.php');
    } else if (isset($_GET['page']) && $_GET['page'] === 'post') {
        include('includes/post.php');
    } else if (isset($_GET['page']) && $_GET['page'] === 'category') {
        include('includes/create_category.php');
    } else if (isset($_GET['page']) && $_GET['page'] === 'form_profile') {
        include('includes/form_profile.php');
    } else {
        include('includes/posts.php');
    }

    include('includes/aside.php'); 
    ?>
</main>

<?php include('includes/footer.php'); ?>
