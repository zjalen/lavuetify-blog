@extends('layouts.app', ['header' => '文章列表'])

@section('content')
    <articles :prop_data='@json($prop_data)'></articles>
@endsection
