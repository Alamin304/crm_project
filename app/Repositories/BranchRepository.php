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
use App\Models\Setting;
use App\Models\Branch;
use App\Models\Bank;


/**
 * Class CustomerRepository
 *
 * @version April 3, 2020, 6:37 am UTC
 */
class BranchRepository extends BaseRepository
{

    protected $fieldSearchable = [
        'company_name',
        'name',
        'website',
        'vat_number',
        'currency_id',
        'city',
        'state',
        'country_id',
        'zip_code',
        'phone',
        'address',
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
        return Branch::class;
    }

    public function create($input)
    {

        $branch = Branch::create(Arr::only($input, [
            'company_name',
            'name',
            'website',
            'vat_number',
            'currency_id',
            'city',
            'state',
            'country_id',
            'zip_code',
            'phone',
            'address',
            'bank_id'
        ]));

        return $branch;
    }
    public function update_branch($supplierId, $input)
    {
        $branch = Branch::findOrFail($supplierId);
        $branch->update(Arr::only($input, [
            'company_name',
            'name',
            'website',
            'vat_number',
            'currency_id',
            'city',
            'state',
            'country_id',
            'zip_code',
            'phone',
            'address',
            'bank_id'
        ]));
        return $branch;
    }

    public function getCountries()
    {
        return Country::pluck('name', 'id');
    }
    public function getCurrencies()
    {
        return Currency::pluck('name', 'id');
    }
    public function getCompanyName()
    {
        return Setting::where('key', 'company')->first();
    }
    public function getBanks()
    {
        return Bank::pluck('name','id');
    }
}
