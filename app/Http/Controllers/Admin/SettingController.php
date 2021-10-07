<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\Admin\SettingRequest;
use App\Http\Requests\Admin\MailConfigurationRequest;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\Setting\SettingRepository;
use DB;

class SettingController extends Controller
{
    protected $setting;

    function __construct(
        SettingRepository $setting
    )
    {
        $this->setting = $setting;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $text = '';
        $data = $this->setting->getById(1);
        if (! empty($data)) {
            $text = 'changes';
        }
        return view('admin.settings.setting')
            ->withSettings($this->setting->getAll())
            ->with('btnText', $text);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SettingRequest $request)
    {
        $id = $this->setting->create($request);
        if ($id > 0) {
            return redirect('admin/settings')->with('flashMessageSuccess','Successfully save.');
        }
        return redirect('admin/settings')->with('flashMessageError','Unable to save ');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function mailConfigure()
    {
        $conf = DB::table('mail_settings')->select('*')->first();
        return view('admin.settings.email-configuration.configure',compact('conf'));
    }

    public function mailConfigureUpdate(MailConfigurationRequest $request)
    {
        $conf = $this->setting->storeMailConfiguration($request);
        if ($conf > 0) {
            return redirect('admin/mail/configuration')->with('flashMessageSuccess','Successfully save.');
        }
        return redirect('admin/mail/configuration')->with('flashMessageError','Unable to save ');
    }
}
