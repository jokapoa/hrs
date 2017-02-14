@extends('layouts.app')

@section('app')
<div id="app">
  <app-navbar @roll="rollView">  {{-- Do not use camelcase like @rollView, beacause this is not .vue file--}}
    <span slot="username">{{ Auth::user()->name }}</span>
    <span slot="csrf-field">{{ csrf_field() }}</span>
  </app-navbar>

  {{-- <transition name="transition-view"> --}}
    <poster v-if="view == 'poster'"> </poster>
    
    <address-book v-if="view == 'addressBook'"
      :members="members"
      :teams="teams"
      :all-columns="memberProps"
      :formatters="memberFormatters"
      :allow-create="isAdmin"
      @roll="rollView"
    > 
    </address-book>

    <profile v-if="view == 'profile'"
      :member="viewkwargs.member"
      :member-props="memberProps"
      :formatters="memberFormatters"
      :allow-edit="isAdmin || viewkwargs.member.id === user.member.id"
      :allow-delete="isAdmin"
    >
  {{-- </transition> --}}
</div>

<script>
  window.bladeVar = {
    isGuest: Boolean({{ Auth::check() }}),

    @if (Auth::check()) 
      isAdmin: Boolean({{ Auth::user()->is_admin }}),
      user: {!! Auth::user()->toJson() !!},
      members: {!! App\Models\Member::has('user')->with('teams')->get()->toJson() !!},
      teams: {!! App\Models\Team::all()->toJson() !!}
    @endif
    // TODO: too big the json, many are duplicated
  }

</script>
@endsection
