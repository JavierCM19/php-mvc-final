
<nav>
  <ul>
    <li><a href="<?= WEB_ROOT ?>/">Home</a></li>
  </ul>
</nav>
<h1>Products</h1>
<?php foreach ($products as $product) : ?>
<p>
  <a href="<?= WEB_ROOT ?>/products/<?= htmlspecialchars($product['id']) ?>/show">
    <?= htmlspecialchars($product["name"])?>
  </a>
</p>
<?php endforeach; ?>

<a href="<?= WEB_ROOT ?>/products/new">New Product</a>

</body>
</html>
