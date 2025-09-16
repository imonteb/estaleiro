<?php

namespace App\Livewire\Equipas;

use Livewire\Component;

use App\Models\PublishedOperationsDay;

class PublishWorkDay extends Component
{
    public $published_day;
    public $success;

    public function mount()
    {
        $last = PublishedOperationsDay::orderByDesc('date')->first();
        $this->published_day = $last?->date;
    }

    public function publishDay()
    {
        $this->validate([
            'published_day' => 'required|date',
        ]);
        // Delete all previous published days to ensure only one row exists
        PublishedOperationsDay::truncate();
        PublishedOperationsDay::create([
            'date' => $this->published_day
        ]);
        $this->success = 'Día publicado actualizado.';
    }

    public function render()
    {
        return view('livewire.equipas.publish-work-day');
    }
}
