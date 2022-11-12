<?php

namespace App\Http\Livewire;

use App\Models\Slider;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class SliderComponent extends Component
{
    use WithPagination, WithFileUploads;
    public $title, $image, $status, $position = 0, $sliderId;
    public $editMode = false;

    public function render()
    {
        return view('livewire.slider-component', [
            'sliders' => Slider::orderBy('position', 'ASC')->paginate(10)
        ]);
    }

    public function updatedImage()
    {
        $this->validate(['image' => 'image|max:1024']);
    }

    public function save()
    {
        try {
            $this->validate([
                'title' => 'required',
                'position' => 'required'
            ]);
            if (!$this->editMode) {
                $filename = 'default-image.jpg';
                if ($this->image) {
                    $extension = $this->image->getClientOriginalExtension();
                    $filename = str_replace(' ', '-', $this->title) . '.' . $extension;
                    $this->image->storeAs('public/sliders', $filename);
                }
                Slider::create([
                    'title' => $this->title,
                    'image' => $filename,
                    'position' => $this->position,
                    'status' => $this->status ?? 1
                ]);
            } else {
                $slider = Slider::find($this->sliderId);
                $filename = $slider->image;
                if ($this->image) {
                    $extension = $this->image->getClientOriginalExtension();
                    $filename = str_replace(' ', '-', $this->title) . '.' . $extension;
                    $this->image->storeAs('public/sliders', $filename);
                }
                $slider->update([
                    'title' => $this->title,
                    'image' => $filename,
                    'position' => $this->position,
                    'status' => $this->status ?? 1
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Line => ' . $e->getLine() . ' Message => ' . $e->getMessage());
            return false;
        }
        $this->emit('reset');
        $this->reset();
        session()->flash('message', 'Guardado correctamente.');

        // return redirect()->to('/slider');
    }

    public function delete()
    {
        try {
            $slider = Slider::find($this->sliderId);
            $slider->delete();
        } catch (\Exception $e) {
            Log::error('Line => ' . $e->getLine() . ' Message => ' . $e->getMessage());
            return false;
        }
        session()->flash('message', 'Eliminado correctamente.');
        $this->emit('reset');
        $this->reset();
    }

    public function setEditMode(Slider $slider)
    {
        $this->sliderId = $slider->id;
        $this->title = $slider->title;
        $this->position = $slider->position;
        $this->status = $slider->status;
        $this->editMode = true;
    }

    public function resetear()
    {
        $this->emit('reset');
        $this->reset();
    }
}
