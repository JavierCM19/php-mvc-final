<nav>
    <ul>
        <li><a href="<?= WEB_ROOT ?>/">Home</a></li>
        <li><a href="<?= WEB_ROOT ?>/products">Products</a></li>
    </ul>
</nav>

<h1>Show Product Page</h1>
<h2><?= $product ?></h2>
<p><?= $description ?></p>

<a href="<?= WEB_ROOT ?>/products/<?= $id?>/edit">Edit product</a>
<br>
<a href="<?= WEB_ROOT ?>/products/<?= $id?>/delete">Delete products</a>
</body>
</html>