
<h1>New Product</h1>

<?php  if (isset($errors["duplicate"])): ?>
    <div class="error" style="color: orangered"><?= $errors["duplicate"] ?></div>
<?php  endif ?>

<form method="post" action="/rem/products/create">

<?php require "form.php" ?>

</form>