{% extends "base.mvc.php" %}

{% block title %}Product{% endblock %}

{% block body %}
<h1>Products</h1>

<a href="/rem/products/new">New Product</a>

<p>Total Products: {{ total }} </p> {% foreach ($products as $product): %}
    <h2>
        <a href="/rem/products/{{ product["id"] }}/show">
        {{ product["name"] }}
        </a>

    </h2>

{% endforeach; %}

{% endblock %}
