<?php

namespace App\Repositories;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use App\Models\Supplier;
use App\Models\CustomerGroup;
use App\Models\Country;
use App\Models\SupplierGroup;
use App\Models\SupplierToGroup;
use App\Models\Currency;

/**
 * Class CustomerRepository
 *
 * @version April 3, 2020, 6:37 am UTC
 */
class SupplierRepository extends BaseRepository
{
    /**
     * @var array
     */

    protected $language = [
        'en' => 'English',
        'es' => 'Spanish',
        'fr' => 'French',
        'de' => 'German',
        'ru' => 'Russian',
        'pt' => 'Portuguese',
        'ar' => 'Arabic',
        'zh' => 'Chinese',
        'tr' => 'Turkish',
    ];

    protected  $currency = [
        '0' => 'INR',
        '1' => 'AUD',
        '2' => 'USD',
        '3' => 'EUR',
        '4' => 'JPY',
        '5' => 'GBP',
        '6' => 'CAD',
    ];
    protected $fieldSearchable = [
        'company_name',
        'vat_number',
        'phone',
        'website',
        'street',
        'city',
        'zip'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Supplier::class;
    }

    public function create($input)
    {

        $supplier = Supplier::create(Arr::only($input, ['company_name', 'vat_number', 'website', 'phone', 'currency', 'country', 'default_language', 'street', 'city', 'zip', 'state']));
        $supplierId = $supplier->id;

        // Initialize array for batch insert
        $dataToInsert = [];
        if (isset($input['groups']) && !empty($input['groups'])) {
            foreach ($input['groups'] as $groupId) {
                $dataToInsert[] = [
                    'supplier_id' => $supplierId,
                    'group_id' => $groupId,
                ];
            }
        }
        if (!empty($dataToInsert)) {
            SupplierToGroup::insert($dataToInsert);
        }
        return $supplier;
    }
    public function update_supplier($supplierId, $input)
    {

        // Find the supplier by ID
        $supplier = Supplier::findOrFail($supplierId);

        // Update supplier data
        $supplier->update(Arr::only($input, ['company_name', 'vat_number', 'website', 'phone', 'currency', 'country', 'default_language', 'street', 'city', 'zip', 'state']));

        // Initialize array for batch insert
        $dataToInsert = [];
        if (isset($input['groups']) && !empty($input['groups'])) {
            foreach ($input['groups'] as $groupId) {
                $dataToInsert[] = [
                    'supplier_id' => $supplierId,
                    'group_id' => $groupId,
                ];
            }
        }

        // First, delete existing groups for the supplier
        SupplierToGroup::where('supplier_id', $supplierId)->delete();

        // Then, insert the new groups
        if (!empty($dataToInsert)) {
            SupplierToGroup::insert($dataToInsert);
        }

        return $supplier;
    }

    public function getSyncList()
    {
        $data = [];
        $data['supplierGroups'] = SupplierGroup::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $data['currencies'] = Currency::pluck('name', 'id');
        $data['languages'] = $this->language;
        $data['countries'] = Country::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        return $data;
    }
    function getGroupData($id)
    {
        return SupplierToGroup::where('supplier_id', $id)->get();
    }
}
