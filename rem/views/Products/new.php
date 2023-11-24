
<h1>New Product</h1>

<?php  if (isset($errors["duplicate"])): ?>
    <div class="error" style="color: orangered"><?= $errors["duplicate"] ?></div>
<?php  endif ?>

<form method="post" action="/rem/products/create">

    <label for="product_id">Product ID</label>
    <input type="number" id="product_id" name="product_id">
    <?php  if (isset($errors["product_id"])): ?>
        <div class="error" style="color: orangered"><?= $errors["product_id"] ?></div>
    <?php  endif ?>

    <label for="name">Name</label>
    <input type="text" id="name" name="name">
    <?php  if (isset($errors["name"])): ?>
        <div class="error" style="color: orangered"><?= $errors["name"] ?></div>
    <?php  endif ?>


    <label for="description">Description</label>
    <textarea id="description" name="description"></textarea>

    <button>Save</button>

</form>