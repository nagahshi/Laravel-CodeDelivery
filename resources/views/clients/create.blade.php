@extends('app')
@section('content')
<div class="container">
    <h3>Novo cliente</h3>
    @include('errors._check')
    <br/>
    {!! Form::open(['route'=>'admin.clients.store','class'=>'form']) !!}

    @include('admin.clients._form')

    <div class="form-group">        
        {!! Form::submit('Criar Cliente',['class'=>'btn btn-primary'])!!}
    </div>   

    {!! Form::close() !!}
</div>
@endsection