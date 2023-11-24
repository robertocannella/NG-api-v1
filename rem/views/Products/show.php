<h1><?= $product["name"] ?></h1>

<table>
    <thead>
    <tr><td>Product Id</td><td>Description</td><td>Edit</td></tr>
    </thead>
    <tbody>
    <tr>
        <td><?= $product["product_id"] ?></td>
        <td><?= $product["description"] ?></td>
        <td><p><a href="/rem/products/<?= $product["id"] ?>/edit">Edit</a></p></td></tr>
    </tbody>
</table>

<div>
    <a href="/rem/products/">All Products</a>
</div>


</body>
</html>