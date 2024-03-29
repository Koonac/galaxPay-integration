@if($errors->All())
    @foreach($errors->All() as $msgError)
        <div class="alert alert-danger shadow mt-2">
            <strong>ERRO:</strong>
            <br>
            {{$msgError}}
        </div>
    @endforeach
@endif

@if(isset($ERROR))
    @foreach($ERROR as $msgError)
        <div class="alert alert-danger shadow mt-2">
            {{$msgError}}
        </div>
    @endforeach
@endif

@if(session('status'))
    <div class="alert alert-success shadow mt-2">
        {{session('status')}}
    </div>
@endif

@if(session('SUCCESS'))
    @foreach(session('SUCCESS') as $msgSuccess)
        <div class="alert alert-success shadow mt-2">
            {{$msgSuccess}}
        </div>
    @endforeach
@endif

@if(isset($SUCCESS))
    @foreach($SUCCESS as $msgSuccess)
        <div class="alert alert-success shadow mt-2">
            {{$msgSuccess}}
        </div>
    @endforeach
@endif

@if(session('WARNING'))
    @foreach(session('WARNING') as $msgWarning)
        <div class="alert alert-warning shadow mt-2">
            {{$msgWarning}}
        </div>
    @endforeach
@endif

@if(isset($WARNING))
    @foreach($WARNING as $msgWarning)
        <div class="alert alert-warning shadow mt-2">
            {{$msgWarning}}
        </div>
    @endforeach
@endif
