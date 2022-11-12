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
        <h1>Areas</h1>
        <form wire:submit.prevent='save' id="form">
            <div class="mb-2">
                <label><b>Nombre: </b></label>
                <input type="text" class="form-control form-control-sm" wire:model="name" required>
                @error('name')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-2">
                <label><b>Objetivo general: </b></label>
                <textarea rows="2" class="form-control" wire:model="general_objective"></textarea>
                @error('general_objective')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-2">
                <label><b>Contacto(email): </b></label>
                <input type="email" class="form-control form-control-sm" wire:model="contact" required>
                @error('contact')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-2">
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
                    <th class="text-center"><i class="fas fa-wrench"></i></th>
                    <th>Nombre</th>
                    <th>Objetivo general</th>
                    <th>Contacto</th>
                    <th>Status</th>
                </thead>
                <tbody>
                    @if (count($areas) > 0)
                        @foreach ($areas as $area)
                            <tr>
                                <td class="text-center">
                                    <div class="d-flex">
                                        <a href="/contests/{{ $area->id }}" class="btn btn-sm btn-warning me-1"
                                            title="Concursos"><i class="fas fa-medal"></i></a>
                                        <a href="/services/{{ $area->id }}" class="btn btn-sm btn-secondary me-1"
                                            title="Servicios"><i class="fas fa-users"></i></a>
                                        <button class="btn btn-sm btn-info me-1" title="Editar"
                                            wire:click='setEditMode({{ $area->id }})'>
                                            <i class="fas fa-pen"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" title="Eliminar" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal"
                                            wire:click="$set('areaId', {{ $area->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                                <td>{{ $area->name }}</td>
                                <td>{{ $area->general_objective }}</td>
                                <td>{{ $area->contact }}</td>
                                <td class="text-center">
                                    @if ($area->status)
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-danger">Inactivo</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center">
                                No hay registros
                            </td>
                        </tr>
                    @endif
                </tbody>
                {{ $areas->links() }}
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
