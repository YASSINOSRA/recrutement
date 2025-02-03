<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\View\View;
use App\Models\EnvSetting;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use App\Repositories\SettingRepository;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\UpdateSettingRequest;
use Brotzka\DotenvEditor\Exceptions\DotEnvException;

/**
 * Class SettingController
 */
class SettingController extends AppBaseController
{
    /** @var SettingRepository */
    private $settingRepository;

    public function __construct(SettingRepository $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Factory|View
     */
    public function index(Request $request): View
    {
        $envData = $this->settingRepository->getEnvData();
        // $envData['mail']['MAIL_USERNAME'] = str_replace('"', '', $envData['mail']['MAIL_USERNAME']);
        // $envData['mail']['MAIL_PASSWORD'] = str_replace('"', '', $envData['mail']['MAIL_PASSWORD']);
        // $envData['mail']['MAIL_FROM_ADDRESS'] = str_replace('"', '', $envData['mail']['MAIL_FROM_ADDRESS']);
        $setting = Setting::pluck('value', 'key')->toArray();
        $setting['phone'] = preparePhoneNumber($setting['phone'], $setting['region_code']);
        $sectionName = ($request->section === null) ? 'general' : $request->section;
        $envSetting = EnvSetting::pluck('value', 'key')->toArray();

        return view("settings.$sectionName",
            compact('setting', 'sectionName','envSetting'))->with($envData);
    }

    /**
     * @throws DotEnvException
     */
    public function update(UpdateSettingRequest $request): RedirectResponse
    {
        $this->settingRepository->updateSetting($request->all());

//        Flash::error('Settings can not be updated on demo.');

        Flash::success(__('messages.flash.setting_update'));
//         in order to clear the cache for .env values
        if ($request->get('sectionName') == 'env_setting') {
            Artisan::call('optimize:clear');
            Artisan::call('config:cache');
        }

        return Redirect::back();
    }
}
