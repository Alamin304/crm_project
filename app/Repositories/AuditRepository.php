<?php
namespace App\Repositories;

use App\Models\Audit;

class AuditRepository
{
    public function create(array $input)
    {
        return Audit::create($input);
    }

    public function update(array $input, $id)
    {
        $audit = Audit::findOrFail($id);
        $audit->update($input);
        return $audit;
    }
}
