<label for="product_id">Product ID</label>

<input
        type="number"
        id="product_id"
        name="product_id"
        {% echo htmlspecialchars( ($action === "edit" || $action === "update") ? "disabled" : "") %}
        {% if (isset($product)): %}
            value="{% echo htmlspecialchars($product["product_id"]) ?? null %}"
        {% endif %}
>


{%  if (isset($errors["product_id"])): %}
    <div class="error" style="color: orangered">{{ errors["product_id"] }}</div>
{% endif %}

<label for="name">Name</label>
<input
        type="text"
        id="name"
        name="name"
        {% if (isset($product)): %}
            value="{% echo htmlspecialchars(($name = $product["name"]) ? $product["name"] : '' )  %}">
        {% endif %}
{%  if (isset($errors["name"])): %}
    <div class="error" style="color: orangered">{{ errors["name"] }}</div>
{%  endif %}

<label for="description">Description</label>
<textarea
        id="description"
        name="description">{% echo htmlspecialchars($product["description"] ?? '') %}</textarea>

<button>Save</button>