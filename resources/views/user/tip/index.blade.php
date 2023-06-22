@extends('layout.default')

@section('title')
    <title>{{ $user->username }} {{ __('user.tips') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('users.show', ['username' => $user->username]) }}" class="breadcrumb__link">
            {{ $user->username }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('earnings.index', ['username' => $user->username]) }}" class="breadcrumb__link">
            {{ __('bon.bonus') }} {{ __('bon.points') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('bon.tips') }}
    </li>
@endsection

@section('nav-tabs')
    @include('user.buttons.user')
@endsection

@section('page', 'page__bonus--tips')

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('bon.tips') }}</h2>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                <tr>
                    <th>{{ __('bon.sender') }}</th>
                    <th>{{ __('bon.receiver') }}</th>
                    <th>{{ __('bon.points') }}</th>
                    <th>{{ __('bon.date') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($tips as $tip)
                    <tr>
                        <td>
                            <x-user_tag :user="$tip->receiverObj" :anon="false" />
                        </td>
                        <td>
                            @switch(true)
                                @case($tip->torrent_id !== null)
                                    <x-user_tag :user="$tip->receiverObj" :anon="$tip->torrent->anon" />
                                    @break
                                @case($tip->post_id !== null)
                                    <x-user_tag :user="$tip->receiverObj" :anon="false" />
                                    @break
                            @endswitch
                        </td>
                        <td>{{ $tip->cost }}</td>
                        <td>{{ $tip->date_actioned }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        {{ $tips->links('partials.pagination') }}
    </section>
@endsection

@section('sidebar')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('bon.your-points') }}</h2>
        <div class="panel__body">{{ $bon }}</div>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('bon.total-tips') }}</h2>
        <dl class="key-value">
            <dt>{{ __('bon.you-have-received-tips') }}</dt>
            <dd>{{ $receivedTips }}</dd>
            <dt>{{ __('bon.you-have-sent-tips') }}</dt>
            <dd>{{ $sentTips }}</dd>
        </div>
    </section>
@endsection
