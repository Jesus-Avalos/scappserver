<?php

namespace App\Http\Livewire;

use App\Models\Area;
use App\Models\Service;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ServicesComponent extends Component
{
    use WithPagination, WithFileUploads;

    public $title, $requirements, $prices, $file_url, $status = 1;
    public $editMode = false, $area, $service_id;

    public function mount($id)
    {
        $this->area = Area::find($id);
    }

    public function render()
    {
        return view('livewire.services-component', [
            'services' => Service::where('area_id', $this->area->id)->paginate(10)
        ]);
    }

    public function save()
    {
        try {
            $this->validate([
                'title' => 'required',
                'file_url' => 'required|mimes:pdf,docx,doc,xls'
            ]);
            $serviceNew = [
                'area_id' => $this->area->id,
                'title' => $this->title,
                'requirements' => $this->requirements,
                'prices' => $this->prices,
                'status' => $this->status
            ];

            if (!$this->editMode) {
                $servicename = '';
                if ($this->file_url) {
                    $name = $this->file_url->getClientOriginalName();
                    $extension = $this->file_url->getClientOriginalExtension();
                    $servicename = Str::slug($name) . '.' . $extension;
                    $this->file_url->storeAs('public/services', $servicename);
                }
                $serviceNew['file_url'] = $servicename;
                Service::create($serviceNew);
            } else {
                $service = Service::find($this->service_id);
                $servicename = $service->file_url;
                if ($this->file_url) {
                    $name = $this->file_url->getClientOriginalName();
                    $extension = $this->file_url->getClientOriginalExtension();
                    $servicename = Str::slug($name) . '.' . $extension;
                    $this->file_url->storeAs('public/services', $servicename);
                }
                $serviceNew['file_url'] = $servicename;
                $service->update($serviceNew);
            }
        } catch (\Exception $e) {
            Log::error('Line => ' . $e->getLine() . ' Message => ' . $e->getMessage());
            return false;
        }
        $this->emit('reset');
        $this->resetExcept('area');
        session()->flash('message', 'Guardado correctamente.');
    }

    public function delete()
    {
        try {
            $service = Service::find($this->service_id);
            $service->delete();
        } catch (\Exception $e) {
            Log::error('Line => ' . $e->getLine() . ' Message => ' . $e->getMessage());
            return false;
        }
        session()->flash('message', 'Eliminado correctamente.');
        $this->emit('reset');
        $this->resetExcept('area');
    }

    public function setEditMode(Service $service)
    {
        $this->service_id = $service->id;
        $this->title = $service->title;
        $this->requirements = $service->requirements;
        $this->prices = $service->prices;
        $this->status = $service->status;
        $this->editMode = true;
    }

    public function resetear()
    {
        $this->emit('reset');
        $this->resetExcept('area');
    }
}
