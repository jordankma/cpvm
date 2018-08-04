<?php

namespace Adtech\Core\App\Http\Controllers;

use Illuminate\Http\Request;
use Adtech\Application\Cms\Controllers\Controller as Controller;
use Adtech\Core\App\Repositories\LocaleRepository;
use Adtech\Core\App\Http\Requests\LocaleRequest;
use Adtech\Core\App\Models\Locale;
use Adtech\Core\App\Models\Domain;
use Spatie\Activitylog\Models\Activity;
use Yajra\Datatables\Datatables;
use Illuminate\Database\QueryException;
use Validator;

class LocaleController extends Controller
{
    private $messages = array(
        'name.regex' => "Sai định dạng",
        'required' => "Bắt buộc",
        'numeric'  => "Phải là số"
    );

    public function __construct(LocaleRepository $localeRepository)
    {
        parent::__construct();
        $this->locale = $localeRepository;
    }

    public function add(LocaleRequest $request)
    {
        $locale = new Locale($request->all());
        $locale->save();

        if ($locale->locale_id) {

            activity('locale')
                ->performedOn($locale)
                ->withProperties($request->all())
                ->log('User: :causer.email - Add Locale - name: :properties.name, locale_id: ' . $locale->locale_id);

            return redirect()->route('adtech.core.locale.manage')->with('success', trans('adtech-core::messages.success.create'));
        } else {
            return redirect()->route('adtech.core.locale.manage')->with('error', trans('adtech-core::messages.error.create'));
        }
    }

    public function create(Request $request)
    {
        $domain_id = $request->input('domain_id', 0);

        return view('ADTECH-CORE::modules.core.locale.create', compact('domain_id'));
    }

    public function delete(LocaleRequest $request)
    {
        $locale_id = $request->input('locale_id');
        $locale = $this->locale->find($locale_id);

        if (null != $locale) {

            $this->locale->delete($locale_id);

            activity('locale')
                ->performedOn($locale)
                ->withProperties($request->all())
                ->log('User: :causer.email - Delete Locale - locale_id: :properties.locale_id, name: ' . $locale->name);

            return redirect()->route('adtech.core.locale.manage')->with('success', trans('adtech-core::messages.success.delete'));
        } else {
            return redirect()->route('adtech.core.locale.manage')->with('error', trans('adtech-core::messages.error.delete'));
        }
    }

    public function manage(Request $request)
    {
        $domains = Domain::all(['domain_id', 'name']);
        $domain_id = $this->domainDefault;
        if ($request->has('domain_id')) {
            $domain_id = $request->input('domain_id');
        }

        return view('ADTECH-CORE::modules.core.locale.manage', compact('domains', 'domain_id'));
    }

    public function show(LocaleRequest $request)
    {
        $locale_id = $request->input('locale_id');
        $locale = $this->locale->find($locale_id);
        $data = [
            'locale' => $locale
        ];

        return view('ADTECH-CORE::modules.core.locale.edit', $data);
    }

    public function update(LocaleRequest $request)
    {
        $locale_id = $request->input('locale_id');
        $locale = $this->locale->find($locale_id);

        if (null != $locale) {
            $locale->name = $request->input('name');
            $locale->alias = $request->input('alias');
            $locale->icon = $request->input('icon');
            $locale->status = ($request->has('status')) ? 1 : 0;

            try {
                if ($locale->save()) {

                    activity('locale')
                        ->performedOn($locale)
                        ->withProperties($request->all())
                        ->log('User: :causer.email - Update Locale - locale_id: :properties.locale_id, name: :properties.name');

                    return redirect()->route('adtech.core.locale.manage')->with('success', trans('adtech-core::messages.success.update'));
                } else {
                    return redirect()->route('adtech.core.locale.show', ['locale_id' => $request->input('locale_id')])->with('error', trans('adtech-core::messages.error.update'));
                }
            } catch (QueryException $e) {
                return redirect()->route('adtech.core.locale.show', ['locale_id' => $request->input('locale_id')])->with('error', trans('adtech-core::messages.error.update'));
            }
        }
    }

    public function getModalDelete(LocaleRequest $request)
    {
        $model = 'locale';
        $confirm_route = $error = null;
        $validator = Validator::make($request->all(), [
            'locale_id' => 'required|numeric',
        ], $this->messages);
        if (!$validator->fails()) {
            try {
                $confirm_route = route('adtech.core.locale.delete', ['locale_id' => $request->input('locale_id')]);
                return view('includes.modal_confirmation', compact('error', 'model', 'confirm_route'));
            } catch (GroupNotFoundException $e) {
                return view('includes.modal_confirmation', compact('error', 'model', 'confirm_route'));
            }
        } else {
            return $validator->messages();
        }
    }

    public function log(Request $request)
    {
        $model = 'locale';
        $confirm_route = $error = null;
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'id' => 'required|numeric',
        ], $this->messages);
        if (!$validator->fails()) {
            try {
                $logs = Activity::where([
                    ['log_name', $model],
                    ['subject_id', $request->input('id')]
                ])->get();
                return view('includes.modal_table', compact('error', 'model', 'confirm_route', 'logs'));
            } catch (GroupNotFoundException $e) {
                return view('includes.modal_table', compact('error', 'model', 'confirm_route'));
            }
        } else {
            return $validator->messages();
        }
    }

    //Table Data to index page
    public function data(Request $request)
    {
        if ($request->has('domain_id')) {
            $domains = $this->locale->findAll($request->input('domain_id'));
            return Datatables::of($domains)
                ->editColumn('status', function ($locales) {
                    if ($locales->status == 1)
                        $status = '<span class="label label-sm label-success">Enable</span>';
                    else
                        $status = '<span class="label label-sm label-warning">Disable</span>';

                    return $status;
                })
                ->editColumn('icon', function ($locales) {
                    $icon = '';
                    if ($locales->icon != '')
                        $icon = '<img src="' . config('site.url_storage') . '/' . $locales->icon . '" height="32px" width="32px"/>';

                    return $icon;
                })
                ->addColumn('actions', function ($locales) {
                    $actions = '';
                    if ($this->user->canAccess('adtech.core.locale.log')) {
                        $actions .= '<a href=' . route('adtech.core.locale.log', ['type' => 'locale', 'id' => $locales->locale_id]) . ' data-toggle="modal" data-target="#log"><i class="livicon" data-name="info" data-size="18" data-loop="true" data-c="#F99928" data-hc="#F99928" title="Log locales"></i></a>';
                    }
                    if ($this->user->canAccess('adtech.core.locale.show')) {
                        $actions .= '<a href=' . route('adtech.core.locale.show', ['locale_id' => $locales->locale_id]) . '><i class="livicon" data-name="edit" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="update locale"></i></a>';
                    }
                    if ($this->user->canAccess('adtech.core.locale.confirm-delete')) {
                        $actions .= '<a href=' . route('adtech.core.locale.confirm-delete', ['locale_id' => $locales->locale_id]) . ' data-toggle="modal" data-target="#delete_confirm"><i class="livicon" data-name="trash" data-size="18" data-loop="true" data-c="#f56954" data-hc="#f56954" title="delete locales"></i></a>';
                    }

                    return $actions;
                })
                ->addIndexColumn()
                ->rawColumns(['actions', 'status', 'icon'])
                ->make();
        }
        return null;
    }
}
