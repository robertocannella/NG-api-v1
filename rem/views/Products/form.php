<label for="product_id">Product ID</label>
<input type="number" id="product_id" name="product_id" <?= ( $action === "edit" || $action === "update" ) ? "disabled" : "" ?> value="<?= $product["product_id"] ?? null; ?>">
<?php  if (isset($errors["product_id"])): ?>
    <div class="error" style="color: orangered"><?= $errors["product_id"] ?></div>
<?php  endif ?>

<label for="name">Name</label>
<input type="text" id="name" name="name" value="<?= $name = $product["name"] ?? '' ?>">
<?php  if (isset($errors["name"])): ?>
    <div class="error" style="color: orangered"><?= $errors["name"] ?></div>
<?php  endif ?>

<label for="description">Description</label>
<textarea id="description" name="description"><?= $product["description"] ?? '' ?></textarea>

<button>Save</button>