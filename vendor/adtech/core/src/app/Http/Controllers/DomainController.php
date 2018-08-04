<?php

namespace Adtech\Core\App\Http\Controllers;

use Illuminate\Http\Request;
use Adtech\Application\Cms\Controllers\Controller as Controller;
use Adtech\Core\App\Repositories\DomainRepository;
use Adtech\Core\App\Http\Requests\DomainRequest;
use Adtech\Core\App\Models\Domain;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Collection;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Filesystem\Filesystem;
use Validator;

class DomainController extends Controller
{
    /**
     * @var Filesystem
     */
    protected $files;

    private $messages = array(
        'name.regex' => "Sai định dạng",
        'required' => "Bắt buộc",
        'numeric'  => "Phải là số"
    );

    public function __construct(DomainRepository $domainRepository, Filesystem $files)
    {
        parent::__construct();
        $this->domain = $domainRepository;
        $this->files = $files;
    }

    public function add(DomainRequest $request)
    {
        $domain = new Domain($request->all());
        $domain->save();

        if ($domain->domain_id) {

            $directory = '../packages/adtech/application/src/configs/' . $domain->name;
            if (!$this->files->isDirectory($directory)) {
                //copy folder chua configs
                mkdir($directory, 0755, true);
                shell_exec('cd ../ && cp -r packages/adtech/application/src/configs/default.local.vn/*' . ' packages/adtech/application/src/configs/' . $domain->name);

                $path = base_path('packages/adtech/application/src/configs/' . $domain->name . '/app.php');
                $appFile = file_get_contents($path);
                file_put_contents($path, str_replace('\/', '/', str_replace('default.local.vn', $domain->name, $appFile)));

                $path = base_path('packages/adtech/application/src/configs/' . $domain->name . '/session.php');
                $sessionFile = file_get_contents($path);
                file_put_contents($path, str_replace('\/', '/', str_replace('default.local.vn', $domain->name, $sessionFile)));
            }

            activity('domain')
                ->performedOn($domain)
                ->withProperties($request->all())
                ->log('User: :causer.email - Add Domain - name: :properties.name, domain_id: ' . $domain->domain_id);

            return redirect()->route('adtech.core.domain.manage')->with('success', trans('adtech-core::messages.success.create'));
        } else {
            return redirect()->route('adtech.core.domain.manage')->with('error', trans('adtech-core::messages.error.create'));
        }
    }

    public function create()
    {
        return view('ADTECH-CORE::modules.core.domain.create');
    }

    public function delete(DomainRequest $request)
    {
        $domain_id = $request->input('domain_id');
        $domain = $this->domain->find($domain_id);

        if (null != $domain) {

            $this->domain->delete($domain_id);

            //Delete folder domain config
            shell_exec('cd ../ && rm -rf packages/adtech/application/src/configs/' . $domain->name);

            activity('domain')
                ->performedOn($domain)
                ->withProperties($request->all())
                ->log('User: :causer.email - Delete Domain - domain_id: :properties.domain_id, name: ' . $domain->name);

            return redirect()->route('adtech.core.domain.manage')->with('success', trans('adtech-core::messages.success.delete'));
        } else {
            return redirect()->route('adtech.core.domain.manage')->with('error', trans('adtech-core::messages.error.delete'));
        }
    }

    public function manage()
    {
        return view('ADTECH-CORE::modules.core.domain.manage');
    }

    public function show(DomainRequest $request)
    {
        $domain_id = $request->input('domain_id');
        $domain = $this->domain->find($domain_id);
        $data = [
            'domain' => $domain
        ];

        return view('ADTECH-CORE::modules.core.domain.edit', $data);
    }

    public function update(DomainRequest $request)
    {
        $domain_id = $request->input('domain_id');

        $domain = $this->domain->find($domain_id);
        $domain->name = $request->input('name');

        if ($domain->save()) {

            activity('domain')
                ->performedOn($domain)
                ->withProperties($request->all())
                ->log('User: :causer.email - Update Domain - domain_id: :properties.domain_id, name: :properties.name');

            return redirect()->route('adtech.core.domain.manage')->with('success', trans('adtech-core::messages.success.update'));
        } else {
            return redirect()->route('adtech.core.domain.show', ['domain_id' => $request->input('domain_id')])->with('error', trans('adtech-core::messages.error.update'));
        }
    }

    public function getModalDelete(DomainRequest $request)
    {
        $model = 'domain';
        $confirm_route = $error = null;
        $validator = Validator::make($request->all(), [
            'domain_id' => 'required|numeric',
        ], $this->messages);
        if (!$validator->fails()) {
            try {
                $confirm_route = route('adtech.core.domain.delete', ['domain_id' => $request->input('domain_id')]);
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
        $model = 'domain';
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
    public function data()
    {
        $domainsDir = base_path() . '/packages/adtech/application/src/configs';
        $ls = @scandir($domainsDir);
        if ($ls) {
            foreach ($ls as $index => $domain_name) {
                if ($this->is_valid_domain_name($domain_name)) {
                    $domain = $this->domain->findBy('name', addslashes($domain_name));
                    if (null === $domain) {
                        $domain = new Domain();
                        $domain->name = addslashes($domain_name);
                        $domain->save();
                    }
                }
            }
        }

        return Datatables::of($this->domain->findAll())
            ->editColumn('name', function ($domains) {
                if ($this->user->canAccess('adtech.core.package.manage')) {
                    return $actions = '<a href=' . route('adtech.core.package.manage', ['id' => $domains->domain_id]) . '>' . $domains->name . '</a>';
                } else {
                    return $domains->name;
                }
            })
            ->addColumn('actions', function ($domains) {
                $actions = '';
                if ($this->user->canAccess('adtech.core.domain.log')) {
                    $actions .= '<a href=' . route('adtech.core.domain.log', ['type' => 'domain', 'id' => $domains->domain_id]) . ' data-toggle="modal" data-target="#log"><i class="livicon" data-name="info" data-size="18" data-loop="true" data-c="#F99928" data-hc="#F99928" title="Log domains"></i></a>';
                }
                if ($this->user->canAccess('adtech.core.package.manage')) {
                    $actions .= '<a href=' . route('adtech.core.package.manage', ['id' => $domains->domain_id]) . '><i class="livicon" data-name="gear" data-size="18" data-loop="true" data-c="#6CC66C" data-hc="#6CC66C" title="package manage"></i></a>';
                }
                if ($this->user->canAccess('adtech.core.domain.show')) {
                    $actions .= '<a href=' . route('adtech.core.domain.show', ['domain_id' => $domains->domain_id]) . '><i class="livicon" data-name="edit" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="update domain"></i></a>';
                }
                if ($this->user->canAccess('adtech.core.domain.confirm-delete')) {
                    $actions .= '<a href=' . route('adtech.core.domain.confirm-delete', ['domain_id' => $domains->domain_id]) . ' data-toggle="modal" data-target="#delete_confirm"><i class="livicon" data-name="trash" data-size="18" data-loop="true" data-c="#f56954" data-hc="#f56954" title="delete domains"></i></a>';
                }

                return $actions;
            })
            ->addIndexColumn()
            ->rawColumns(['actions', 'name'])
            ->make();
    }

    function is_valid_domain_name($domain_name)
    {
        return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $domain_name) //valid chars check
            && preg_match("/^.{1,253}$/", $domain_name) //overall length check
            && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domain_name)   ); //length of each label
    }
}
