<?php
namespace App\Repositories;

use App\Models\MembershipCardTemplate;
use Illuminate\Support\Facades\Auth;

class MembershipCardTemplateRepository
{
    public function all()
    {
        return MembershipCardTemplate::latest()->get();
    }

    public function find($id)
    {
        return MembershipCardTemplate::findOrFail($id);
    }

    public function create(array $input)
    {
        $input['added_by'] = Auth::id();
        return MembershipCardTemplate::create($input);
    }

    public function update($id, array $input)
    {
        $template = $this->find($id);
        $template->update($input);
        return $template;
    }

    public function delete($id)
    {
        $template = $this->find($id);
        return $template->delete();
    }
}
