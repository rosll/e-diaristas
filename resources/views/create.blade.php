@extends('app')

@section('titulo', 'Criar Diaristas')

@section('conteudo')
  <h1>Criar Diaristas</h1>
  <form action="{{ route('diaristas.store') }}" method="post" enctype="multipart/form-data">
    @include('_form')
  </form>
@endsection
