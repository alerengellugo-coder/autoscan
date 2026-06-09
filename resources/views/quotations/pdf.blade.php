<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: Helvetica, Arial, sans-serif; font-size: 12px; color: #1f2937; line-height: 1.5; }
    .container { max-width: 800px; margin: 0 auto; padding: 20px; }

    /* Header */
    .header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 3px solid #2563eb; padding-bottom: 20px; margin-bottom: 24px; }
    .company-name { font-size: 22px; font-weight: bold; color: #2563eb; }
    .company-info { font-size: 10px; color: #6b7280; margin-top: 4px; }
    .quotation-title { text-align: right; }
    .quotation-number { font-size: 20px; font-weight: bold; color: #1f2937; }
    .quotation-date { font-size: 11px; color: #6b7280; margin-top: 2px; }
    .quotation-status { display: inline-block; padding: 2px 10px; border-radius: 10px; font-size: 10px; font-weight: 600; margin-top: 4px; }
    .status-draft { background: #f3f4f6; color: #374151; }
    .status-pending { background: #fef3c7; color: #92400e; }
    .status-approved { background: #d1fae5; color: #065f46; }
    .status-rejected { background: #fee2e2; color: #991b1b; }

    /* Client & Vehicle */
    .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px; }
    .info-card { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; padding: 14px; }
    .info-label { font-size: 10px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }
    .info-value { font-size: 13px; font-weight: 600; color: #1f2937; }
    .info-detail { font-size: 11px; color: #4b5563; margin-top: 2px; }

    /* Notes */
    .notes-section { margin-bottom: 24px; padding: 12px; background: #fffbeb; border: 1px solid #fde68a; border-radius: 6px; }
    .notes-label { font-size: 10px; font-weight: 600; color: #92400e; text-transform: uppercase; margin-bottom: 4px; }
    .notes-text { font-size: 11px; color: #78350f; white-space: pre-wrap; }

    /* Items Table */
    .items-table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
    .items-table thead th { background: #1f2937; color: white; font-size: 10px; font-weight: 600; text-transform: uppercase; padding: 8px 10px; text-align: left; }
    .items-table thead th:last-child, .items-table thead th:nth-child(3), .items-table thead th:nth-child(4), .items-table thead th:nth-child(5) { text-align: right; }
    .items-table tbody td { padding: 8px 10px; border-bottom: 1px solid #e5e7eb; font-size: 11px; }
    .items-table tbody td:last-child, .items-table tbody td:nth-child(3), .items-table tbody td:nth-child(4), .items-table tbody td:nth-child(5) { text-align: right; }
    .items-table tbody tr:nth-child(even) { background: #f9fafb; }
    .item-name { font-weight: 600; }

    /* Totals */
    .totals { display: flex; justify-content: flex-end; }
    .totals-box { width: 260px; }
    .totals-row { display: flex; justify-content: space-between; padding: 4px 0; font-size: 12px; }
    .totals-row.discount { color: #dc2626; }
    .totals-divider { border-top: 2px solid #1f2937; margin-top: 6px; padding-top: 8px; }
    .totals-total { display: flex; justify-content: space-between; font-size: 16px; font-weight: bold; color: #2563eb; }
    .totals-label { color: #6b7280; }
    .totals-value { color: #1f2937; font-weight: 500; }

    /* Footer */
    .footer { margin-top: 40px; padding-top: 16px; border-top: 1px solid #e5e7eb; display: flex; justify-content: space-between; font-size: 9px; color: #9ca3af; }
    .terms { max-width: 500px; font-size: 9px; color: #6b7280; margin-top: 20px; padding: 10px; background: #f3f4f6; border-radius: 4px; }
</style>
</head>
<body>
<div class="container">

    {{-- Header --}}
    <div class="header">
        <div>
            <div class="company-name">AutoScan</div>
            <div class="company-info">Servicio Automotriz Profesional</div>
        </div>
        <div class="quotation-title">
            <div class="quotation-number">Cotizacion #{{ $quotation->quotation_number }}</div>
            <div class="quotation-date">Fecha: {{ $quotation->created_at?->format('d/m/Y') ?? '—' }}</div>
            @if($quotation->valid_until)
            <div class="quotation-date">Valida hasta: {{ $quotation->valid_until?->format('d/m/Y') ?? '—' }}</div>
            @endif
            <div class="quotation-status @switch($quotation->status?->value)
                @case('draft') status-draft @break
                @case('pending_client') status-pending @break
                @case('approved') status-approved @break
                @case('rejected') status-rejected @break
                @default status-draft @break
            @endswitch">
                {{ $quotation->status_label ?? 'Borrador' }}
            </div>
        </div>
    </div>

    {{-- Client & Vehicle --}}
    <div class="info-grid">
        <div class="info-card">
            <div class="info-label">Cliente</div>
            <div class="info-value">{{ $quotation->client->name ?? '—' }}</div>
            @if($quotation->client->email ?? false)
            <div class="info-detail">{{ $quotation->client->email }}</div>
            @endif
            @if($quotation->client->phone ?? false)
            <div class="info-detail">{{ $quotation->client->phone }}</div>
            @endif
        </div>
        <div class="info-card">
            <div class="info-label">Vehiculo</div>
            @if($quotation->vehicle)
            <div class="info-value">{{ $quotation->vehicle->brand ?? '' }} {{ $quotation->vehicle->model ?? '' }}</div>
            <div class="info-detail">Placa: {{ $quotation->vehicle->plate ?? '—' }}</div>
            @if($quotation->vehicle->year)
            <div class="info-detail">Ano: {{ $quotation->vehicle->year }}</div>
            @endif
            @else
            <div class="info-value">No asignado</div>
            @endif
        </div>
    </div>

    {{-- Notes --}}
    @if($quotation->notes)
    <div class="notes-section">
        <div class="notes-label">Notas</div>
        <div class="notes-text">{{ $quotation->notes }}</div>
    </div>
    @endif

    {{-- Items Table --}}
    <table class="items-table">
        <thead>
            <tr>
                <th style="width:5%">#</th>
                <th style="width:35%">Descripcion</th>
                <th style="width:10%">Cant.</th>
                <th style="width:20%">P. Unitario</th>
                <th style="width:15%">Desc.</th>
                <th style="width:15%">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quotation->items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td class="item-name">{{ $item->name ?? ($item->description ?? 'Item') }}</td>
                <td>{{ number_format($item->quantity, 0) }}</td>
                <td>${{ number_format($item->unit_price, 2) }}</td>
                <td>{{ $item->discount > 0 ? '$' . number_format($item->discount, 2) : '—' }}</td>
                <td style="font-weight:600">${{ number_format($item->total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Totals --}}
    <div class="totals">
        <div class="totals-box">
            <div class="totals-row">
                <span class="totals-label">Subtotal:</span>
                <span class="totals-value">${{ number_format($quotation->subtotal ?? 0, 2) }}</span>
            </div>
            <div class="totals-row">
                <span class="totals-label">Impuestos ({{ number_format($quotation->tax_rate ?? 0, 0) }}%):</span>
                <span class="totals-value">${{ number_format($quotation->tax ?? 0, 2) }}</span>
            </div>
            @if($quotation->discount > 0)
            <div class="totals-row discount">
                <span class="totals-label">Descuento:</span>
                <span>-${{ number_format($quotation->discount, 2) }}</span>
            </div>
            @endif
            <div class="totals-divider">
                <div class="totals-total">
                    <span>TOTAL:</span>
                    <span>${{ number_format($quotation->total ?? 0, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Terms --}}
    @if($quotation->terms_and_conditions)
    <div class="terms">
        <strong>Terminos y Condiciones:</strong><br>
        {{ $quotation->terms_and_conditions }}
    </div>
    @endif

    {{-- Footer --}}
    <div class="footer">
        <span>AutoScan - Cotizacion #{{ $quotation->quotation_number }}</span>
        <span>Generada el {{ $quotation->created_at?->format('d/m/Y H:i') ?? '—' }}</span>
    </div>

</div>
</body>
</html>
