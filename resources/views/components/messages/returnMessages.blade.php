@if($errors->All())
    @foreach($errors->All() as $msgError)
        <div class="alert alert-danger mt-4">
            {{$msgError}}
        </div>
    @endforeach
@endif

@if(session('SUCCESS'))
    @foreach(session('SUCCESS') as $msgSuccess)
        <div class="alert alert-success mt-4">
            {{$msgSuccess}}
        </div>
    @endforeach
@endif