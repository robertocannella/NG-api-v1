<h1><?= $product["name"] ?></h1>

<table>
    <thead>
    <tr><td>Product Id</td><td>Description</td></tr>
    </thead>
    <tbody>
    <tr><td><?= $product["product_id"] ?></td><td><?= $product["description"] ?></td></tr>
    </tbody>
</table>

<div>
    <a href="/rem/products/">All Products</a>
</div>


</body>
</html>