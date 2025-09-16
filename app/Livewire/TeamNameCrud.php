<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\TeamName;

class TeamNameCrud extends Component
{
    public $teamNames = [];
    public $showModal = false;
    public $editMode = false;
    public $teamNameId = null;
    public $name = '';
    public $showDeleteConfirmModal = false;
    public $deleteCandidateId = null;

    protected function rules()
    {
        $uniqueRule = 'unique:teams_names_tables,name';
        if ($this->editMode && $this->teamNameId) {
            $uniqueRule .= ',' . $this->teamNameId;
        }
        return [
            'name' => 'required|string|max:255|' . $uniqueRule,
        ];
    }

    public function mount()
    {
        $this->loadTeamNames();
    }

    public function loadTeamNames()
    {
        $this->teamNames = TeamName::all();
    }

    public function showCreateModal()
    {
        $this->reset(['name', 'teamNameId', 'editMode']);
        $this->showModal = true;
    }

    public function showEditModal($id)
    {
        $team = TeamName::findOrFail($id);
        $this->teamNameId = $team->id;
        $this->name = $team->name;
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate($this->rules());
        if ($this->editMode) {
            $team = TeamName::findOrFail($this->teamNameId);
            $team->update(['name' => $this->name]);
        } else {
            TeamName::create(['name' => $this->name]);
        }
        $this->loadTeamNames();
        $this->showModal = false;
        $this->dispatch('teamNamesUpdated');
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
            TeamName::findOrFail($this->deleteCandidateId)->delete();
            $this->loadTeamNames();
            $this->dispatch('teamNamesUpdated');
        }
        $this->cancelDelete();
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    protected $listeners = ['openTeamNameModal' => 'showCreateModal'];

    public function render()
    {
        return view('livewire.team-name-crud');
    }
}
