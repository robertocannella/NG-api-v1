{% extends "base.mvc.php" %}

{% block title %}Product{% endblock %}

{% block body %}
<h1>New Product</h1>


{% if (isset($errors["duplicate"])): %}
<div class="error" style="color: orangered">{{ errors["duplicate"] }} </div>
{% endif %}


<form method="post" action="/rem/products/create">

{% include "Products/form.mvc.php" %}

</form>
{% endblock %}