<div class="modal fade" id="{{$modalId}}" tabindex="-1" aria-labelledby="formModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase" id="formModalTitle">{{$title}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{$route}}" method="POST" id="IdFormModal">
                    @method('PUT')
                    @csrf
                    {{ $slot }}
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="IdFormModal" class="btn btn-info text-white fw-bold">Salvar alterações</button>
            </div>
        </div>
    </div>
</div>