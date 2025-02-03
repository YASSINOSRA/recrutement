<?php
namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\FormBuilder;

class FormBuilderTable extends Component
{
    use WithPagination;

    public function render()
    {
        $forms = FormBuilder::paginate(10); // 10 résultats par page
        return view('livewire.form-builder-table', compact('forms'));
    }

    protected $listeners = ['delete'];

    public function delete($id)
    {
        try {
            $form = FormBuilder::findOrFail($id);
            $form->delete();
            session()->flash('message', 'Form deleted successfully!');
            $this->emit('formDeleted'); // Optionally emit an event after deletion
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete the form.');
        }
    }

}

