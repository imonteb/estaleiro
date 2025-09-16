<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Pep;

class PepCrud extends Component
{
    public $peps = [];
    public $showModal = false;
    public $editMode = false;
    public $pepId = null;
    public $code = '';
    public $description = '';
    public $showDeleteConfirmModal = false;
    public $deleteCandidateId = null;

    protected function rules()
    {
        $uniqueRule = 'unique:peps,code';
        if ($this->editMode && $this->pepId) {
            $uniqueRule .= ',' . $this->pepId;
        }
        return [
            'code' => 'required|string|max:255|' . $uniqueRule,
            'description' => 'nullable|string|max:255',
        ];
    }

    public function mount()
    {
        $this->loadPeps();
    }

    public function loadPeps()
    {
        $this->peps = Pep::active()->get();
    }

    public function showCreateModal()
    {
        $this->reset(['code', 'description', 'pepId', 'editMode']);
        $this->showModal = true;
    }

    public function showEditModal($id)
    {
        $pep = Pep::findOrFail($id);
        $this->pepId = $pep->id;
        $this->code = $pep->code;
        $this->description = $pep->description;
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate($this->rules());
        if ($this->editMode) {
            $pep = Pep::findOrFail($this->pepId);
            $pep->update(['code' => $this->code, 'description' => $this->description]);
        } else {
            Pep::create(['code' => $this->code, 'description' => $this->description, 'active' => true]);
        }
        $this->loadPeps();
        $this->showModal = false;
        $this->dispatch('pepsUpdated');
    }

    public function confirmDelete($id)
    {
        $this->deleteCandidateId = $id;
        $this->showDeleteConfirmModal = true;
    }

    public function cancelDelete()
    {
        $this->showDeleteConfirmModal = false;
        $this->deleteCandidateId = null;
    }

    public function deleteConfirmed()
    {
        Pep::findOrFail($this->deleteCandidateId)->delete();
        $this->loadPeps();
        $this->showDeleteConfirmModal = false;
        $this->deleteCandidateId = null;
        $this->dispatch('pepsUpdated');
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    protected $listeners = ['openPepModal' => 'showCreateModal'];

    public function render()
    {
        return view('livewire.pep-crud');
    }
}
