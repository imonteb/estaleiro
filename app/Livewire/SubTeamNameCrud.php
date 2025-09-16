<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\SubTeamName;

class SubTeamNameCrud extends Component
{
    public $subTeamNames = [];
    public $showModal = false;
    public $editMode = false;
    public $subTeamNameId = null;
    public $name = '';
    public $showDeleteConfirmModal = false;
    public $deleteCandidateId = null;

    protected function rules()
    {
        $uniqueRule = 'unique:sub_team_names,name';
        if ($this->editMode && $this->subTeamNameId) {
            $uniqueRule .= ',' . $this->subTeamNameId;
        }
        return [
            'name' => 'required|string|max:255|' . $uniqueRule,
        ];
    }

    public function mount()
    {
        $this->loadSubTeamNames();
    }

    public function loadSubTeamNames()
    {
        $this->subTeamNames = SubTeamName::all();
    }

    public function showCreateModal()
    {
        $this->reset(['name', 'subTeamNameId', 'editMode']);
        $this->showModal = true;
    }

    public function showEditModal($id)
    {
        $sub = SubTeamName::findOrFail($id);
        $this->subTeamNameId = $sub->id;
        $this->name = $sub->name;
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate($this->rules());
        if ($this->editMode) {
            $sub = SubTeamName::findOrFail($this->subTeamNameId);
            $sub->update(['name' => $this->name]);
        } else {
            SubTeamName::create(['name' => $this->name, 'active' => true]);
        }
        $this->loadSubTeamNames();
        $this->showModal = false;
        $this->dispatch('subTeamNamesUpdated');
    }

    public function confirmDelete($id)
    {
        $this->deleteCandidateId = $id;
        $this->showDeleteConfirmModal = true;
    }

    public function cancelDelete()
    {
        $this->deleteCandidateId = null;
        $this->showDeleteConfirmModal = false;
    }

    public function deleteConfirmed()
    {
        if ($this->deleteCandidateId) {
            SubTeamName::findOrFail($this->deleteCandidateId)->delete();
            $this->loadSubTeamNames();
            $this->dispatch('subTeamNamesUpdated');
        }
        $this->cancelDelete();
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    protected $listeners = ['openSubTeamNameModal' => 'showCreateModal'];

    public function render()
    {
        return view('livewire.sub-team-name-crud');
    }
}
