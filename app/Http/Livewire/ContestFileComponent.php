<?php

namespace App\Http\Livewire;

use App\Models\Contest;
use App\Models\ContestFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ContestFileComponent extends Component
{
    use WithPagination, WithFileUploads;
    public $contest, $editMode = false, $file_id;
    public $title, $file_url, $status = 1;

    public function mount($id)
    {
        $this->contest = Contest::find($id);
    }

    public function render()
    {
        return view('livewire.contest-file-component', [
            'files' => ContestFile::paginate(10)
        ]);
    }

    public function save()
    {
        try {
            $this->validate([
                'title' => 'required',
                'file_url' => 'required|mimes:pdf,docx,doc,xls'
            ]);
            if (!$this->editMode) {
                if ($this->file_url) {
                    $name = $this->file_url->getClientOriginalName();
                    $extension = $this->file_url->getClientOriginalExtension();
                    $filename = Str::slug($name) . '.' . $extension;
                    $this->file_url->storeAs('public/contestfiles', $filename);
                }
                ContestFile::create([
                    'contest_id' => $this->contest->id,
                    'title' => $this->title,
                    'file_url' => $filename,
                    'status' => $this->status
                ]);
            } else {
                $file = ContestFile::find($this->file_id);
                $filename = $file->file_url;
                if ($this->file_url) {
                    $name = $this->file_url->getClientOriginalName();
                    $extension = $this->file_url->getClientOriginalExtension();
                    $filename = Str::slug($name) . '.' . $extension;
                    $this->file_url->storeAs('public/contestfiles', $filename);
                }
                $file->update([
                    'title' => $this->title,
                    'file_url' => $filename,
                    'status' => $this->status
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Line => ' . $e->getLine() . ' Message => ' . $e->getMessage());
            return false;
        }
        $this->emit('reset');
        $this->resetExcept('contest');
        session()->flash('message', 'Guardado correctamente.');
    }

    public function delete()
    {
        try {
            $file = ContestFile::find($this->file_id);
            $file->delete();
        } catch (\Exception $e) {
            Log::error('Line => ' . $e->getLine() . ' Message => ' . $e->getMessage());
            return false;
        }
        session()->flash('message', 'Eliminado correctamente.');
        $this->emit('reset');
        $this->resetExcept('contest');
    }

    public function setEditMode(ContestFile $file)
    {
        $this->file_id = $file->id;
        $this->title = $file->title;
        $this->status = $file->status;
        $this->editMode = true;
    }

    public function resetear()
    {
        $this->emit('reset');
        $this->resetExcept('contest');
    }
}
