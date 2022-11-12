<div class="container">
    <form wire:submit.prevent="sendPush">
        <h1>Notificaciones Push</h1>

        <div class="mb-2">
            <label>Título</label>
            <input type="text" class="form-control" wire:model="title" required>
            @error('title')
                <span class="error text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-2">
            <label>Cuerpo</label>
            <input type="text" class="form-control" wire:model="body" required>
            @error('body')
                <span class="error text-danger">{{ $message }}</span>
            @enderror
        </div>
        <br>
        @if (!$enviar)
            <div class="text-center">
                <button type="button" class="btn btn-primary" wire:click="$set('enviar', true)">Enviar</button>
            </div>
        @else
            <div>
                <div class="text-center">
                    <h3>¿Confirmas que deseas enviar la notificación?</h3>
                </div>
                <div class="d-flex justify-content-around">
                    <button type="submit" class="btn btn-success" style="width: 100px">Sí</button>
                    <button type="button" class="btn btn-secondary" style="width: 100px"
                        wire:click="$set('enviar', false)">No</button>
                </div>
            </div>
        @endif
    </form>
</div>
