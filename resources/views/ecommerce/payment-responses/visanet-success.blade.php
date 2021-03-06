@extends('ecommerce.layouts.site')

@section('title', 'Respuesta medio de pago - Amolca Editorial Médica y Odontológica')

<!--Add checkout styles-->
@section('styles')
<link rel="stylesheet" href="{{ asset('css/ecommerce/checkout.css') }}">
@endsection

@section('contentClass', 'payment-response')
@section('content')

	<table class="payment-response striped">
		<thead>
			<tr>
				<th colspan="2">Respuesta de pago - Pedido # {{ $response['orden_id'] }}</th>
			</tr>
		</thead>

		<tbody>

			@if ( isset($response['authorization_code']) )
				<tr class="authorization_code code_00">
					<th>
						Código de autorización:
					</th>
					<td>
						{{ $response['authorization_code'] }}
					</td>
				</tr>
			@endif

			@if ( isset($response['response_text']) )
				<tr class="response_text">
					<th>
						Respuesta de transacción:
					</th>
					<td>
						{{ $response['response_text'] }}
					</td>
				</tr>
			@endif

			@if ( isset($response['credit_card']) )
				<tr>
					<th>
						Número de la tarjeta de crédito:
					</th>
					<td>
						{{ $response['credit_card'] }}
					</td>
				</tr>
			@endif

			@if ( isset($response['orden_id']) )
				<tr>
					<th>
						Referencía única del pedido:
					</th>
					<td>
						{{ $response['orden_id'] }}
					</td>
				</tr>
			@endif

			@if ( isset($response['action_code']) )
				<tr>
					<th>
						Codigo de accion:
					</th>
					<td>
						{{ $response['action_code'] }}
					</td>
				</tr>
			@endif

			@if ( isset($response['transaction_date']) )
				<tr>
					<th>
						Fecha y hora de la transacción:
					</th>
					<td>
						@php
						$timestamp=(int) $response['transaction_date'] / 1000;
						@endphp
						{{ print_r(date('j F, Y', $timestamp)) }}
					</td>
				</tr>
			@endif

			@if ( isset($response['transaction_id']) )
				<tr>
					<th>
						Número ID de la transacción:
					</th>
					<td>
						{{ $response['transaction_id'] }}
					</td>
				</tr>
			@endif

			<tr class="finish-row">
				<td colspan="2">
					<a href="/" class="button primary">Regresar al inicio</a>
				</td>
			</tr>

		</tbody>

	</table>

@endsection
