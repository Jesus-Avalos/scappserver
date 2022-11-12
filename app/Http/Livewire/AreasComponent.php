<?php

namespace App\Http\Livewire;

use App\Models\Area;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class AreasComponent extends Component
{
    use WithPagination;
    public $name, $general_objective, $contact, $status = 1, $areaId;
    public $editMode = false;

    public function render()
    {
        return view('livewire.areas-component', [
            'areas' => Area::paginate(10)
        ]);
    }

    public function save()
    {
        try {
            $this->validate([
                'name' => 'required',
                'general_objective' => 'required',
                'contact' => 'required'
            ]);
            $areaObj = [
                'name' => $this->name,
                'general_objective' => $this->general_objective,
                'contact' => $this->contact,
                'status' => $this->status
            ];
            if (!$this->editMode) {
                Area::create($areaObj);
            } else {
                $area = Area::find($this->areaId);
                $area->update($areaObj);
            }
        } catch (\Exception $e) {
            Log::error('Line => ' . $e->getLine() . ' Message => ' . $e->getMessage());
            return false;
        }
        $this->emit('reset');
        $this->reset();
        session()->flash('message', 'Guardado correctamente.');

        // return redirect()->to('/area');
    }

    public function delete()
    {
        try {
            $area = Area::find($this->areaId);
            $area->delete();
        } catch (\Exception $e) {
            Log::error('Line => ' . $e->getLine() . ' Message => ' . $e->getMessage());
            return false;
        }
        session()->flash('message', 'Eliminado correctamente.');
        $this->emit('reset');
        $this->reset();
    }

    public function setEditMode(Area $area)
    {
        $this->areaId = $area->id;
        $this->name = $area->name;
        $this->general_objective = $area->general_objective;
        $this->contact = $area->contact;
        $this->status = $area->status;
        $this->editMode = true;
    }

    public function resetear()
    {
        $this->emit('reset');
        $this->reset();
    }
}
