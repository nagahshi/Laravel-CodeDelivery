@extends('app')
@section('content')
<div class="container">
    <h3>Novo Produto</h3>
    @include('errors._check')
    <br/>
    {!! Form::open(['route'=>'admin.products.store','class'=>'form']) !!}

    @include('admin.products._form')

    <div class="form-group">        
        {!! Form::submit('Criar Produto',['class'=>'btn btn-primary'])!!}
    </div>   

    {!! Form::close() !!}
</div>
@endsection