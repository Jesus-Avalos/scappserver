<?php

namespace App\Http\Livewire;

use App\Models\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class EventsComponent extends Component
{
    use WithPagination, WithFileUploads;
    public $title, $description, $primary_image, $secondary_image, $status = 1, $eventId;
    public $editMode = false;

    public function render()
    {
        return view('livewire.events-component', [
            'events' => Event::paginate(10)
        ]);
    }

    public function updatedPrimaryImage()
    {
        $this->validate(['primary_image' => 'image|max:1024']);
    }

    public function updatedSecondaryImage()
    {
        $this->validate(['secondary_image' => 'image|max:1024']);
    }

    public function save()
    {
        try {
            $this->validate([
                'title' => 'required',
                'description' => 'required',
                'primary_image' => 'required',
            ]);
            $eventNew = [];
            $eventNew['title'] = $this->title;
            $eventNew['description'] = $this->description;
            $eventNew['status'] = $this->status;
            if (!$this->editMode) {
                $primary_filename = 'default-image.jpg';
                if ($this->primary_image) {
                    $primary_filename = $this->storeImages('primary');
                }
                $secondary_filename = 'default-image.jpg';
                if ($this->secondary_image) {
                    $secondary_filename = $this->storeImages('secondary');
                }
                $eventNew['primary_image'] = $primary_filename;
                $eventNew['secondary_image'] = $secondary_filename;
                Event::create($eventNew);
            } else {
                $event = Event::find($this->eventId);
                $primary_filename = $event->primary_image;
                if ($this->primary_image) {
                    $primary_filename = $this->storeImages('primary');
                }
                $secondary_filename = $event->secondary_image;
                if ($this->secondary_image) {
                    $secondary_filename = $this->storeImages('secondary');
                }
                $eventNew['primary_image'] = $primary_filename;
                $eventNew['secondary_image'] = $secondary_filename;
                $event->update($eventNew);
            }
        } catch (\Exception $e) {
            Log::error($e);
            Log::error('Line => ' . $e->getLine() . ' Message => ' . $e->getMessage());
            return false;
        }
        $this->emit('reset');
        $this->reset();
        session()->flash('message', 'Guardado correctamente.');

        // return redirect()->to('/event');
    }

    public function delete()
    {
        try {
            $event = Event::find($this->eventId);
            $event->delete();
        } catch (\Exception $e) {
            Log::error('Line => ' . $e->getLine() . ' Message => ' . $e->getMessage());
            return false;
        }
        session()->flash('message', 'Eliminado correctamente.');
        $this->emit('reset');
        $this->reset();
    }

    public function setEditMode(Event $event)
    {
        $this->eventId = $event->id;
        $this->title = $event->title;
        $this->description = $event->description;
        $this->status = $event->status;
        $this->editMode = true;
    }

    public function resetear()
    {
        $this->emit('reset');
        $this->reset();
    }

    function storeImages($type)
    {
        try {
            $image = $type === 'primary' ? $this->primary_image : $this->secondary_image;
            $extension = $image->getClientOriginalExtension();
            $filename = Str::slug($this->title) . '.' . $extension;
            $image->storeAs('public/events/' . $type, $filename);
        } catch (\Exception $e) {
            Log::error('Line => ' . $e->getLine() . ' Message => ' . $e->getMessage());
            return 'default-image.jpg';
        }

        return $filename;
    }
}
