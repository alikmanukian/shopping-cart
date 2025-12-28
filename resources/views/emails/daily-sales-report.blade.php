<x-mail::message>
# Daily Sales Report

**Date:** {{ $report['date'] }}

<br>
<br>

## Summary

<x-mail::table>
| Metric | Value |
|--------|-------|
| Total Orders | {{ $report['total_orders'] }} |
| Total Revenue | ${{ $report['total_revenue'] }} |
| Items Sold | {{ $report['total_items_sold'] }} |
</x-mail::table>



<br>
<br>

@if($report['top_products']->isNotEmpty())
## Top Selling Products

<x-mail::table>
| Product | Quantity | Revenue |
|---------|----------|---------|
@foreach($report['top_products'] as $product)
| {{ $product['name'] }} | {{ $product['quantity'] }} | ${{ number_format($product['revenue'], 2) }} |
@endforeach
</x-mail::table>
@endif

@if($report['total_orders'] === 0)
No sales were recorded on this date.
@endif

<x-mail::button :url="config('app.url')">
View Dashboard
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
