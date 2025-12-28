<x-mail::message>
# Low Stock Alert

The following product is running low on stock:

**Product:** {{ $product->name }}

**Current Stock:** {{ $product->stock_quantity }} units

**Threshold:** {{ $threshold }} units

Please restock this product as soon as possible to avoid stockouts.

<x-mail::button :url="config('app.url')">
View Dashboard
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
