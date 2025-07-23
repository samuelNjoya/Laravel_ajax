@extends('temp\base')
@section('title','index')

@section('content')
     <h2>my index page</h2>
     {{ "<strong>Hello word</strong>" }}
     <a href="{{ route('user') }}">user</a>
@endsection