<?php

namespace App\Http\Livewire;

use App\Models\Area;
use App\Models\Contest;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;

class ContestsComponent extends Component
{
    use WithPagination;

    public $area, $title, $status = 1, $contest_id, $editMode = false;

    public function mount($id)
    {
        $this->area = Area::find($id);
    }

    public function render()
    {
        return view('livewire.contests-component', [
            'contests' => Contest::where('area_id', $this->area->id)->paginate(10)
        ]);
    }

    public function save()
    {
        try {
            $this->validate([
                'title' => 'required',
            ]);
            $contestNew = [
                'area_id' => $this->area->id,
                'title' => $this->title,
                'status' => $this->status
            ];
            if (!$this->editMode) {
                Contest::create($contestNew);
            } else {
                $contest = Contest::find($this->contest_id);
                $contest->update($contestNew);
            }
        } catch (\Exception $e) {
            Log::error('Line => ' . $e->getLine() . ' Message => ' . $e->getMessage());
            return false;
        }
        $this->emit('reset');
        $this->resetExcept('area');
        session()->flash('message', 'Guardado correctamente.');

        // return redirect()->to('/contest');
    }

    public function delete()
    {
        try {
            $contest = Contest::find($this->contest_id);
            $contest->delete();
        } catch (\Exception $e) {
            Log::error('Line => ' . $e->getLine() . ' Message => ' . $e->getMessage());
            return false;
        }
        session()->flash('message', 'Eliminado correctamente.');
        $this->emit('reset');
        $this->resetExcept('area');
    }

    public function setEditMode(Contest $contest)
    {
        $this->contest_id = $contest->id;
        $this->title = $contest->title;
        $this->status = $contest->status;
        $this->editMode = true;
    }

    public function resetear()
    {
        $this->emit('reset');
        $this->resetExcept('area');
    }
}
