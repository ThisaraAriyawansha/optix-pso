@props(['status' => 'gray', 'label' => null])

@php
$colorMap = [
    'success' => 'badge-success',
    'paid'    => 'badge-success',
    'active'  => 'badge-success',
    'received' => 'badge-success',
    'ready'   => 'badge-success',
    'danger'  => 'badge-danger',
    'cancelled' => 'badge-danger',
    'refunded' => 'badge-danger',
    'defaulted' => 'badge-danger',
    'warning' => 'badge-warning',
    'partial' => 'badge-warning',
    'overdue' => 'badge-warning',
    'waiting_parts' => 'badge-warning',
    'in_repair' => 'badge-warning',
    'diagnosing' => 'badge-warning',
    'info'    => 'badge-info',
    'issued'  => 'badge-info',
    'ordered' => 'badge-info',
    'primary' => 'badge-primary',
    'draft'   => 'badge-gray',
    'gray'    => 'badge-gray',
    'delivered' => 'badge-gray',
    'expired' => 'badge-gray',
    'rejected' => 'badge-danger',
    'converted' => 'badge-success',
    'accepted' => 'badge-success',
    'sent'    => 'badge-info',
];
$class = $colorMap[$status] ?? 'badge-gray';
$text = $label ?? ucwords(str_replace('_', ' ', $status));
@endphp

<span class="badge {{ $class }}">{{ $text }}</span>
