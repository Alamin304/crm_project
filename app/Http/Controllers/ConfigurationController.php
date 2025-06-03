<?php

namespace App\Http\Controllers;

use App\DataTables\MembershipCardTemplateDataTable;
use App\Http\Requests\MembershipCardTemplateRequest;
use App\Models\CurrencyRatesSetting;
use App\Models\LoyaltySetting;
use App\Queries\MembershipCardTemplateQuery;
use App\Repositories\CurrencyRatesSettingRepository;
use App\Repositories\LoyaltySettingRepository;
use App\Repositories\MembershipCardTemplateRepository;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ConfigurationController extends AppBaseController
{
    private $loyaltySettingRepository;
    private $membershipCardTemplateRepository;
    private $currencyRatesSettingRepository;

    public function __construct(
        LoyaltySettingRepository $loyaltyRepo,
        MembershipCardTemplateRepository $membershipCardRepo,
        CurrencyRatesSettingRepository $currencyRatesRepo
    ) {
        $this->loyaltySettingRepository = $loyaltyRepo;
        $this->membershipCardTemplateRepository = $membershipCardRepo;
        $this->currencyRatesSettingRepository = $currencyRatesRepo;
    }

    // public function index()
    // {
    //     $loyaltySettings = $this->loyaltySettingRepository->getSettings();
    //     $currencyRatesSettings = $this->currencyRatesSettingRepository->getSettings();

    //     return view('configuration.index', compact('loyaltySettings', 'currencyRatesSettings'));
    // }

    public function index()
    {
        $loyaltySettings = $this->loyaltySettingRepository->getSettings();
        $currencyRatesSettings = $this->currencyRatesSettingRepository->getSettings();

        // Static client groups
        $clientGroups = [
            ['id' => 'asdfasdf', 'name' => 'asdfasdf'],
            ['id' => 'azezae', 'name' => 'azezae'],
            ['id' => 'SELLER', 'name' => 'SELLER'],
            // Add more as needed
        ];

        // Static clients
        $clients = [
            ['id' => '123', 'name' => '123'],
            ['id' => '123456', 'name' => '123456'],
            ['id' => 'aaaa', 'name' => 'aaaa'],
            ['id' => 'dfasfas', 'name' => 'dfasfas'],
            // Add more as needed
        ];

        return view('configuration.index', compact(
            'loyaltySettings',
            'currencyRatesSettings',
            'clientGroups',
            'clients'
        ));
    }

    public function updateLoyaltySettings(Request $request)
    {
        $input = $request->all();
        $input['hidden_client_groups'] = json_encode($request->hidden_client_groups ?? []);
        $input['hidden_clients'] = json_encode($request->hidden_clients ?? []);

        $this->loyaltySettingRepository->update($input);
      return redirect()
        ->route('configuration.index') // replace with your index route
        ->with('success', 'Loyalty settings updated successfully.');
        // return $this->sendSuccess('Loyalty settings updated successfully.');
    }

    // public function membershipCardTemplates(MembershipCardTemplateDataTable $dataTable)
    // {
    //     return $dataTable->render('configuration.membership_card_templates.index');
    // }
// public function membershipCardTemplates(MembershipCardTemplateDataTable $dataTable)
// {
//     return view('configuration.membership_card_templates.index', [
//         'dataTable' => $dataTable
//     ]);
// }

public function membershipCardTemplates(Request $request, MembershipCardTemplateQuery $query)
{
    if ($request->ajax()) {
        return DataTables::of($query->get())
            ->addColumn('action', function ($template) {
                return view('configuration.membership_card_templates.action', compact('template'))->render();
            })
            ->editColumn('created_at', function ($template) {
                return $template->created_at->format('Y-m-d H:i:s');
            })
            // ->editColumn('added_by', function ($template) {
            //     return optional($template->addedBy)->name;
            // })
            ->rawColumns(['action']) // if 'action' column has HTML
            ->make(true);
    }

    return view('configuration.membership_card_templates.index');
}



    public function createMembershipCardTemplate()
    {
        return view('configuration.membership_card_templates.create');
    }

    public function storeMembershipCardTemplate(MembershipCardTemplateRequest $request)
    {
        $input = $request->all();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('membership_card_templates', 'public');
            $input['image_path'] = $path;
        }

        $this->membershipCardTemplateRepository->create($input);

         return redirect()
        ->route('configuration.index') // replace with your index route
        ->with('success', 'Membership card template created successfully.');

        // return $this->sendSuccess('Membership card template created successfully.');
    }

    public function editMembershipCardTemplate($id)
    {
        $template = $this->membershipCardTemplateRepository->find($id);
        return view('configuration.membership_card_templates.edit', compact('template'));
    }

    public function updateMembershipCardTemplate($id, MembershipCardTemplateRequest $request)
    {
        $input = $request->all();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('membership_card_templates', 'public');
            $input['image_path'] = $path;
        }

        $this->membershipCardTemplateRepository->update($id, $input);

        return redirect()
        ->route('configuration.index') // replace with your index route
        ->with('success', 'Membership card template updated successfully.');
        // return $this->sendSuccess('Membership card template updated successfully.');
    }

    public function destroyMembershipCardTemplate($id)
    {
        $this->membershipCardTemplateRepository->delete($id);
        return $this->sendSuccess('Membership card template deleted successfully.');
    }

    public function updateCurrencyRatesSettings(Request $request)
    {
        $this->currencyRatesSettingRepository->update($request->all());
        // return $this->sendSuccess('Currency rates settings updated successfully.');

         return redirect()
        ->route('configuration.index') // replace with your correct route name
        ->with('success', 'Currency rates settings updated successfully.');
    }
}
