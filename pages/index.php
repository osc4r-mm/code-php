<?php 
session_start();
include('includes/header.php');
include('config/utility.php');

$messages = get_messages();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['page'])) {
    $_SESSION['current_page'] = $_POST['page'];
}
?>

<main class="container">
    <!-- Canvia l'include -->
    <?php 
    if (isset($_GET['page'])) {
        if ($_GET['page'] === 'form_post') {
            include('includes/form_post.php');
        } else if ($_GET['page'] === 'post') {
            include('includes/post.php');
        } else if ($_GET['page'] === 'category') {
            include('includes/create_category.php');
        } else if ($_GET['page'] === 'form_profile') {
            include('includes/form_profile.php');
        } else if ($_GET['page'] === 'about') {
            include('includes/about.php');
        } else if ($_GET['page'] === 'contact') {
            include('includes/contact.php');
        } else if ($_GET['page'] === 'categories') {
            include('includes/category.php');
        }
    } else {
        include('includes/posts.php');
    }

    include('includes/aside.php'); 
    ?>
</main>

<?php include('includes/footer.php'); ?>
