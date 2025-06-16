<?php
namespace App\Http\Controllers;

use App\Http\Requests\CreateWorkingHourRequest;
use App\Http\Requests\UpdateWorkingHourRequest;
use App\Http\Requests\CreateUnitOfMeasureCategoryRequest;
use App\Http\Requests\UpdateUnitOfMeasureCategoryRequest;
use App\Http\Requests\CreateUnitOfMeasureRequest;
use App\Http\Requests\UpdateUnitOfMeasureRequest;
use App\Http\Requests\CreateMaterialCodeRequest;
use App\Http\Requests\UpdateMaterialCodeRequest;
use App\Models\WorkingHour;
use App\Models\UnitOfMeasureCategory;
use App\Models\UnitOfMeasure;
use App\Models\MaterialCode;
use App\Queries\WorkingHourDataTable;
use App\Queries\UnitOfMeasureCategoryDataTable;
use App\Queries\UnitOfMeasureDataTable;
use App\Queries\MaterialCodeDataTable;
use App\Repositories\WorkingHourRepository;
use App\Repositories\UnitOfMeasureCategoryRepository;
use App\Repositories\UnitOfMeasureRepository;
use App\Repositories\MaterialCodeRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class ManufacturingSettingController extends AppBaseController
{
    private $workingHourRepo;
    private $unitOfMeasureCategoryRepo;
    private $unitOfMeasureRepo;
    private $materialCodeRepo;

    public function __construct(
        WorkingHourRepository $workingHourRepo,
        UnitOfMeasureCategoryRepository $unitOfMeasureCategoryRepo,
        UnitOfMeasureRepository $unitOfMeasureRepo,
        MaterialCodeRepository $materialCodeRepo
    ) {
        $this->workingHourRepo = $workingHourRepo;
        $this->unitOfMeasureCategoryRepo = $unitOfMeasureCategoryRepo;
        $this->unitOfMeasureRepo = $unitOfMeasureRepo;
        $this->materialCodeRepo = $materialCodeRepo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $section = $request->get('section');

            switch ($section) {
                case 'working_hours':
                    return DataTables::of((new WorkingHourDataTable())->get())->make(true);
                case 'unit_of_measure_categories':
                    return DataTables::of((new UnitOfMeasureCategoryDataTable())->get())->make(true);
                case 'unit_of_measures':
                    return DataTables::of((new UnitOfMeasureDataTable())->get())->make(true);
                case 'material_codes':
                    return DataTables::of((new MaterialCodeDataTable())->get())->make(true);
            }
        }

        return view('manufacturing_settings.index');
    }

    // Working Hours Methods
    public function storeWorkingHour(CreateWorkingHourRequest $request)
    {
        $input = $request->all();
        try {
            $workingHour = $this->workingHourRepo->create($input);
            return $this->sendResponse($workingHour, __('messages.settings.working_hours.saved'));
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function updateWorkingHour(WorkingHour $workingHour, UpdateWorkingHourRequest $request)
    {
        $input = $request->all();
        $workingHour = $this->workingHourRepo->update($input, $workingHour->id);
        return $this->sendSuccess(__('messages.settings.working_hours.saved'));
    }

    public function destroyWorkingHour(WorkingHour $workingHour)
    {
        try {
            $workingHour->delete();
            return $this->sendSuccess(__('messages.settings.working_hours.delete'));
        } catch (QueryException $e) {
            return $this->sendError('Failed To delete!! Already in use.');
        }
    }

    // Unit of Measure Category Methods
    public function storeUnitOfMeasureCategory(CreateUnitOfMeasureCategoryRequest $request)
    {
        $input = $request->all();
        try {
            $category = $this->unitOfMeasureCategoryRepo->create($input);
            return $this->sendResponse($category, __('messages.settings.unit_of_measure_categories.saved'));
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function updateUnitOfMeasureCategory(UnitOfMeasureCategory $unitOfMeasureCategory, UpdateUnitOfMeasureCategoryRequest $request)
    {
        $input = $request->all();
        $category = $this->unitOfMeasureCategoryRepo->update($input, $unitOfMeasureCategory->id);
        return $this->sendSuccess(__('messages.settings.unit_of_measure_categories.saved'));
    }

    public function destroyUnitOfMeasureCategory(UnitOfMeasureCategory $unitOfMeasureCategory)
    {
        try {
            $unitOfMeasureCategory->delete();
            return $this->sendSuccess(__('messages.settings.unit_of_measure_categories.delete'));
        } catch (QueryException $e) {
            return $this->sendError('Failed To delete!! Already in use.');
        }
    }

    // Unit of Measure Methods
    public function storeUnitOfMeasure(CreateUnitOfMeasureRequest $request)
    {
        $input = $request->all();
        try {
            $unit = $this->unitOfMeasureRepo->create($input);
            return $this->sendResponse($unit, __('messages.settings.unit_of_measures.saved'));
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function updateUnitOfMeasure(UnitOfMeasure $unitOfMeasure, UpdateUnitOfMeasureRequest $request)
    {
        $input = $request->all();
        $unit = $this->unitOfMeasureRepo->update($input, $unitOfMeasure->id);
        return $this->sendSuccess(__('messages.settings.unit_of_measures.saved'));
    }

    public function destroyUnitOfMeasure(UnitOfMeasure $unitOfMeasure)
    {
        try {
            $unitOfMeasure->delete();
            return $this->sendSuccess(__('messages.settings.unit_of_measures.delete'));
        } catch (QueryException $e) {
            return $this->sendError('Failed To delete!! Already in use.');
        }
    }

    // Material Code Methods
    public function storeMaterialCode(CreateMaterialCodeRequest $request)
    {
        $input = $request->all();
        try {
            $materialCode = $this->materialCodeRepo->create($input);
            return $this->sendResponse($materialCode, __('messages.settings.material_codes.saved'));
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function updateMaterialCode(MaterialCode $materialCode, UpdateMaterialCodeRequest $request)
    {
        $input = $request->all();
        $materialCode = $this->materialCodeRepo->update($input, $materialCode->id);
        return $this->sendSuccess(__('messages.settings.material_codes.saved'));
    }

    public function destroyMaterialCode(MaterialCode $materialCode)
    {
        try {
            $materialCode->delete();
            return $this->sendSuccess(__('messages.settings.material_codes.delete'));
        } catch (QueryException $e) {
            return $this->sendError('Failed To delete!! Already in use.');
        }
    }
}
