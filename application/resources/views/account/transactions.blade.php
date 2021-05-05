@extends('layouts.app')

@section('content')
    @if (count($transactions))
        <table class="table">
            <thead>
            <tr>
                <th scope="col">{{ __('ID') }}</th>
                <th scope="col">{{ __('Type') }}</th>
                <th scope="col">{{ __('Sum') }}</th>
                <th scope="col">{{ __('Date') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($transactions as $transaction)
                <tr>
                    <th scope="row">{{ $transaction->id }}</th>
                    <td>{{ __('transactions.types.' . $transaction->type) }}</td>
                    <td>{{ number_format($transaction->amount, 2) }}</td>
                    <td>{{ date_format($transaction->created_at, 'd.m.Y') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <h1>{{ __('There is no transactions.') }}</h1>
    @endif

@endsection
