<div class="container">
    <div>
        <div>
            @if (session()->has('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>{{ session('message') }}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
        <h1>Servicios área {{ $area->name }}</h1>
        <form wire:submit.prevent='save' id="form">
            <div class="mb-1">
                <label><b>Título: </b></label>
                <input type="text" class="form-control form-control-sm" wire:model="title" required>
                @error('title')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-1">
                <label><b>Requisitos: </b></label>
                <textarea class="form-control" rows="2" wire:model="requirements"></textarea>
                @error('requirements')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-1">
                <label><b>Precios: </b></label>
                <textarea class="form-control" rows="2" wire:model="prices"></textarea>
                @error('prices')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-1">
                <label><b>Archivo: </b></label>
                @if ($editMode)
                    <input type="file" class="form-control form-control-sm" wire:model="file_url">
                @else
                    <input type="file" class="form-control form-control-sm" wire:model="file_url" required>
                @endif
                <div wire:loading wire:target="file_url">Uploading...</div>
                @error('file_url')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-1">
                <label><b>Status: </b></label>
                <select class="form-select form-select-sm" wire:model="status">
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
                @error('status')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="text-center mt-2">
                <button type="button" class="btn btn-secondary"
                    wire:click="resetear">{{ $editMode ? 'Cancelar' : 'Reset' }}</button>
                <button type="submit" class="btn btn-primary">{{ $editMode ? 'Actualizar' : 'Crear' }}</button>
            </div>
        </form>
    </div>
    <div>
        <h1>Registros</h1>
        <div class="table-responsive">
            <table class="table table-bordered table-dense">
                <thead class="bg-dark text-white">
                    <th>Título</th>
                    <th>Requisitos</th>
                    <th>Precios</th>
                    <th>Archivo</th>
                    <th>Status</th>
                    <th class="text-center"><i class="fas fa-wrench"></i></th>
                </thead>
                <tbody>
                    @if (count($services) > 0)
                        @foreach ($services as $service)
                            <tr>
                                <td>{{ $service->title }}</td>
                                <td>{{ $service->requirements }}</td>
                                <td>{{ $service->prices }}</td>
                                <td class="text-center">{{ $service->file_url }}</td>
                                <td class="text-center">
                                    @if ($service->status)
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-info" wire:click='setEditMode({{ $service->id }})'>
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal"
                                        wire:click="$set('service_id', {{ $service->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="text-center">
                                No hay registros
                            </td>
                        </tr>
                    @endif
                </tbody>
                {{ $services->links() }}
            </table>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="exampleModalLabel">Confirmar eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <h5><b>¿Quieres continuar con el proceso de eliminación?</b></h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" wire:click="delete">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
</div>
