@extends('layouts.app')

@section('content')
    <table class="table">
        <thead>
            <tr>
                {{-- ID, Сумма вклада, Процент, Количество текущих начислений, Сумма начислений, Статус депозита, Дата --}}
                <th scope="col">{{ __('ID') }}</th>
                <th scope="col">{{ __('Invested amount') }}</th>
                <th scope="col">{{ __('Percent') }}</th>
                <th scope="col">{{ __('Accrue times') }}</th>
                <th scope="col">{{ __('Accrue amount') }}</th>
                <th scope="col">{{ __('Status') }}</th>
                <th scope="col">{{ __('Date') }}</th>
            </tr>
        </thead>
        <tbody>
        @foreach($deposits as $deposit)
            <tr>
                <th scope="row">{{ $deposit->id }}</th>
                <th>{{ number_format($deposit->invested, 2) }}</th>
                <th>{{ __(':percent%', ['percent' => $deposit->percent]) }}</th>
                <th>{{ $deposit->accrue_times }}</th>
                <th>{{ number_format($deposit->accrueAmount(), 2) }}</th>
                <th>{{ __('deposits.statuses.' . $deposit->active) }}</th>
                <td>{{ date_format($deposit->created_at, 'd.m.Y') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
