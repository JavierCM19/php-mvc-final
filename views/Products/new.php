<nav>
    <ul>
        <li><a href= "<?= WEB_ROOT ?>/">Home</a></li>
        <li><a href= "<?= WEB_ROOT ?>/products">Products</a></li>
    </ul>
</nav>

<h1>New Product</h1>

<form action= "<?= WEB_ROOT ?>/products/create" method="post">

<!-- include the form contents from the new form.php -->
<?php require "form.php" ?>

</form>

</body>
</html>