<h1>Delete Product</h1>

<p><a href="/rem/products/<?= $product["id"] ?>/show">Cancel</a></p>


<form method="post" action="/rem/products/<?= $product["id"] ?>/destroy">

<p>Are you sure?</p>

    <button>Yes</button>

</form>
