{% extends "base.mvc.php" %}

{% block title %}Edit Product{% endblock %}

{% block body %}
<h1>Edit Product</h1>

<p><a href="/rem/products/{{ product["id"] }}/show">Cancel</a></p>

<form method="post" action="/rem/products/{{ product["id"] }}/update">

{% include "Products/form.mvc.php" %}

</form>


{% endblock %}