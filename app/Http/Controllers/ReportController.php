<?php

namespace App\Http\Controllers;

use App\Exports\ITTariffByProjectExport;
use App\Exports\ITTariffExport;
use App\Exports\ResourceHistoryExport;
use App\Models\AssignService;
use App\Models\ExchangeRates;
use App\Models\Project;
use App\Models\Server;
use App\Models\Sow;
use App\Models\SplaAssignedDiscount;
use App\Models\SplaLicense;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function resource_consumption()
    {
        /* get url previous accest this */
        $url = url()->previous();
        $split_url = explode('/', $url);

        [$servers, $date_start, $date_end, $name_vm_or_hm] = $this->getCacheSavedOrSaveCacheOrDeleteCache(['servers', 'date_start', 'date_end', 'name_vm_or_hm'], [], '');

        $this->getCacheSavedOrSaveCacheOrDeleteCache([], ['servers', 'date_start', 'date_end', 'name_vm_or_hm'], [], 'DELETE');

        if (sizeof($split_url) === 7 && $split_url[3] === 'reports' && isset($servers)) {
            return view('reports.IT-resources-consumption', ['servers' => $servers, 'date_start' => $date_start, 'date_end' => $date_end, 'name' => $name_vm_or_hm]);
        }

        $servers = Server::selectRaw(
            'servers.idserver, servers.name,
            servers.active, servers.idproject,
            projects.name AS project_name, jsonb_object_agg(resources_history.name,resources_history.amount) as resources,
            servers.service'
        )->join('projects', function ($join) {
            $join->on('projects.idproject', '=', 'servers.idproject');
        })->join('resources_history', function ($join) {
            $join->on('resources_history.idserver', '=', 'servers.idserver');
        })->groupBy(['servers.idserver', 'servers.name', 'servers.active', 'servers.idproject', 'project_name', 'service', 'resources_history.date'])
            ->orderBy('resources_history.date', 'asc')->get()->unique();

        return view('reports.IT-resources-consumption', ['servers' => $servers]);
    }

    /* public function resource_consumption_for_project_name(Request $request)
    {
        $servers = Server::selectRaw(
            'servers.idserver, servers.name,
            servers.active, servers.idproject,
            projects.name AS project_name, jsonb_object_agg(resources_history.name,resources_history.amount) as resources,
            servers.service'
        )->join('projects', function ($join) {
            $join->on('projects.idproject', '=', 'servers.idproject');
        })->join('resources_history', function ($join) {
            $join->on('resources_history.idserver', '=', 'servers.idserver');
            $join->limit(4);
        })->where('projects.name', 'like', '%' . strtoupper($request->name) . '%');

        if ($request->date_start != '' && $request->date_end != '') {
            $servers->whereBetween('resources_history.date', [$request->date_start, $request->date_end]);
        }
        $servers = $servers->groupBy(['servers.idserver', 'servers.name', 'servers.active', 'servers.idproject', 'project_name', 'service', 'resources_history.date'])
            ->orderBy('resources_history.date', 'asc')->get()->unique();

        $this->getCacheSavedOrSaveCache(['servers', 'date_start', 'date_end', 'name_project'], [$servers, $request->date_start, $request->date_end, $request->name, $request->name], 'save');

        Cache::put('servers', $servers, 60 * 60);
        Cache::put('date_start', $request->date_start, 60 * 60);
        Cache::put('date_end', $request->date_end, 60 * 60);
        Cache::put('name_project', $request->name, 60 * 60);

        return response()->json($servers);
    } */

    public function resource_consumption_for_hostname_or_vmware(Request $request)
    {
        $servers = Server::selectRaw(
            'servers.idserver, servers.name,
            servers.active, servers.idproject,
            projects.name AS project_name, jsonb_object_agg(resources_history.name,resources_history.amount) as resources,
            servers.service'
        )->join('projects', function ($join) {
            $join->on('projects.idproject', '=', 'servers.idproject');
        })->join('resources_history', function ($join) {
            $join->on('resources_history.idserver', '=', 'servers.idserver');
        })->where('servers.hostname', 'like', '%' . strtoupper($request->name) . '%')
            ->orWhere('servers.machine_name', 'like', '%' . strtoupper($request->name) . '%');

        if ($request->date_start != '' && $request->date_end != '') {
            $servers->whereBetween('resources_history.date', [$request->date_start, $request->date_end]);
        }
        $servers = $servers->groupBy(['servers.idserver', 'servers.name', 'servers.active', 'servers.idproject', 'project_name', 'service', 'resources_history.date'])
            ->orderBy('resources_history.date', 'asc')->get()->unique();

        $this->getCacheSavedOrSaveCacheOrDeleteCache(['servers', 'date_start', 'date_end', 'name_vm_or_hm'], [$servers, $request->date_start, $request->date_end, $request->name], 'SAVE');

        return response()->json($servers);
    }

    public function resource_consumption_btween_dates(Request $request)
    {
        $servers = Server::selectRaw(
            'servers.idserver, servers.name,
            servers.active, servers.idproject,
            projects.name AS project_name, jsonb_object_agg(resources_history.name,resources_history.amount) as resources,
            servers.service'
        )->join('projects', function ($join) {
            $join->on('projects.idproject', '=', 'servers.idproject');
        })->join('resources_history', function ($join) {
            $join->on('resources_history.idserver', '=', 'servers.idserver');
        });

        if (isset($request->date_start) && isset($request->date_end)) {
            $servers = $servers->whereBetween('resources_history.date', [$request->date_start, $request->date_end]);
        }

        $servers = $servers->groupBy(['servers.idserver', 'servers.name', 'servers.active', 'servers.idproject', 'project_name', 'service', 'resources_history.date'])
            ->orderBy('resources_history.date', 'asc')->get()->unique();

        $this->getCacheSavedOrSaveCacheOrDeleteCache(['servers', 'date_start', 'date_end'], [$servers, $request->date_start, $request->date_end], 'SAVE');

        return response()->json($servers);
    }

    public function generate_report_resource_history($date_start, $date_end, $idserver)
    {
        return Excel::download(new ResourceHistoryExport($date_start, $date_end, $idserver), 'historial_de_recursos_generado_' . Carbon::now()->format('d_m_Y') . '_.xlsx');
    }

    public function generate_report_it_tariff($date_start, $date_end, $idproject)
    {
        $id_project = $idproject == 'na' ? null : $idproject;

        $date_start_ = str_replace('-', '/', $date_start);
        $date_end_ = str_replace('-', '/', $date_end);

        [$servers, $sows, $spla_assigned_discounts, $cost_maintenance] = $this->getServersAndSowsForCalculateCosts($date_start_, $date_end_, $id_project);
        [$filters, $resources] = $this->get_servers_and_resources_filters($servers);
        $costs = $idproject == 'na' ?
            $this->getCostsByProject($filters, $resources, $sows, $spla_assigned_discounts, $cost_maintenance, $date_start_, $date_end_) :
            $this->getCostsByServer($filters, $resources, $sows, $spla_assigned_discounts, $date_start_, $date_end_);

        $exchange_rate = ExchangeRates::all()->first();

        return $idproject == 'na' ?
            Excel::download(new ITTariffExport($costs, $exchange_rate), 'tarifario_generado_' . Carbon::now()->format('d_m_Y') . '_.xlsx') :
            Excel::download(new ITTariffByProjectExport($costs, $exchange_rate), 'tarifario_generado_' . Carbon::now()->format('d_m_Y') . '_.xlsx');
    }

    public function resource_consumption_grafic($id, $date_start, $date_end)
    {

        if ($date_start == 'na' || $date_end == 'na') {
            $date_ = date('01/m/Y', strtotime(date('Y-m-d') . '-1 month'));
            [$date_start, $date_end] = [$date_, date('d/m/Y')];
        }

        $server = Server::selectRaw('
            servers.name, resources_history.name as resource_name ,resources_history.amount,resources_history.date
        ')->join('resources_history', function ($join) use ($date_start, $date_end) {
            $join->on('resources_history.idserver', '=', 'servers.idserver');
            $join->whereBetween('resources_history.date', [$date_start, $date_end]);
        })->where('servers.idserver', $id)->get();

        if (sizeof($server) == 0) {
            $server = Server::selectRaw('
               servers.name, resources_history.name as resource_name ,resources_history.amount,resources_history.date
           ')->join('resources_history', function ($join) {
                $join->on('resources_history.idserver', '=', 'servers.idserver');
            })->where('servers.idserver', $id)
                ->orderBy('resources_history.date', 'desc')->limit(32)->get();
        }

        return view('reports.grafics', ['server' => $server, 'name' => $server[0]->name, 'date_start' => $date_start, 'date_end' => $date_end, 'idserver' => $id]);
    }

    public function resource_consumption_it_tariff()
    {

        /* get url previous accest this */
        $url = url()->previous();
        $split_url = explode('/', $url);

        [$costs, $date_start, $date_end] = $this->getCacheSavedOrSaveCacheOrDeleteCache(['costs', 'date_start', 'date_end'], [], 'GET');

        $this->getCacheSavedOrSaveCacheOrDeleteCache(['costs', 'date_start', 'date_end'], [], 'DELETE');

        if (sizeof($split_url) === 10 && $split_url[5] === 'tariff' && $split_url[6] === 'project' && isset($costs)) {
            return view('reports.IT-tariff', ['costs' => $costs, 'date_start' => $date_start, 'date_end' => $date_end]);
        }

        [$date_start, $date_end] = $this::getDatesCalculed();

        [$servers, $sows, $spla_assigned_discounts, $cost_maintenance] = $this->getServersAndSowsForCalculateCosts($date_start, $date_end, null);
        [$filters, $resources] = $this->get_servers_and_resources_filters($servers);

        $costs = $this->getCostsByProject($filters, $resources, $sows, $spla_assigned_discounts, $cost_maintenance, null, null);

        return view('reports.IT-tariff', ['costs' => $costs, 'date_start' => $date_start, 'date_end' => $date_end]);
    }

    public function resource_consumption_it_tariff_by_project($id, $date_start, $date_end)
    {
        if ($date_start === 'na' && $date_end === 'na') [$date_start, $date_end] = $this::getDatesCalculed();
        else [$date_start, $date_end] = [str_replace('-', '/', $date_start), str_replace('-', '/', $date_end)];

        $project = Project::find($id);

        [$servers, $sows, $spla_assigned_discounts] = $this->getServersAndSowsForCalculateCosts($date_start, $date_end, $id);
        [$filters, $resources] = $this->get_servers_and_resources_filters($servers);
        $costs = $this->getCostsByServer($filters, $resources, $sows, $spla_assigned_discounts, null, null);

        $request = new Request(['date_start' => $date_start, 'date_end' => $date_end]);
        $this->resource_consumption_it_tariff_btween_dates($request);

        return view('reports.IT-tariff-server', ['costs' => $costs, 'project_name' => $project->name, 'date_start' => $date_start, 'date_end' => $date_end, 'idproject' => $id]);
    }

    public function resource_consumption_it_tariff_btween_dates(Request $request)
    {

        [$servers, $sows, $spla_assigned_discounts, $cost_maintenance] = $this->getServersAndSowsForCalculateCosts($request->date_start, $request->date_end, null);
        [$filters, $resources] = $this->get_servers_and_resources_filters($servers);
        $costs = $this->getCostsByProject($filters, $resources, $sows, $spla_assigned_discounts, $cost_maintenance, $request->date_start, $request->date_end);

        $this->getCacheSavedOrSaveCacheOrDeleteCache(['costs', 'date_start', 'date_end'], [$costs, $request->date_start, $request->date_end], 'SAVE');

        return response()->json($costs);
    }

    public function server_summary()
    {
        $servers = Server::selectRaw(
            'servers.idserver, servers.name AS server_name,servers.machine_name,servers.hostname,servers.service,
            servers.active,servers.is_deleted,projects.name AS project_name,projects.idproject as alp,
            sows.name AS sow_name,sows.version,sows.type,sows.idsow'
        )->join('projects', function ($join) {
            $join->on('projects.idproject', '=', 'servers.idproject');
        })->leftJoin('sows', function ($join) {
            $join->on('sows.idsow', '=', 'servers.idsow');
        })->orderBy('servers.is_deleted', 'asc')->get();

        $licenses = SplaLicense::select('idspla', 'name', 'type')->where('is_deleted', '=', false)->orderBy('type', 'asc')->get();
        $sows = Sow::select('idsow', 'name', 'version', 'type')->where('is_deleted', '=', false)->get();

        $resources = Server::selectRaw('
                servers.idserver, resources_history.date,
                jsonb_object_agg(resources_history.name,resources_history.amount) as resources
        ')->join('resources_history', function ($join) {
            $join->on('resources_history.idserver', '=', 'servers.idserver');
        })->groupBy(['servers.idserver', 'resources_history.date'])
            ->orderBy('resources_history.date', 'asc')->get()->unique();

        $assign_services = AssignService::select('assign_services.idserver', 'is_backup', 'is_additional', 'is_additional_spla', 'is_windows_license', 'is_antivirus', 'is_linux_license')
            ->join('servers', function ($join) {
                $join->on('servers.idserver', '=', 'assign_services.idserver');
            })->get();

        $assign_splas = SplaAssignedDiscount::select('iddiscount', 'percentage', 'idserver', 'spla_licenses.type', 'spla_licenses.idspla')
            ->join('spla_licenses', function ($join) {
                $join->on('spla_licenses.idspla', '=', 'spla_assigned_discounts.idspla');
            })->get();

        $projects = Project::all();

        return view('reports.server-summary', [
            'servers' => $servers, 'licenses' => $licenses, 'sows' => $sows, 'resources' => $resources,
            'assign_services' => $assign_services, 'assign_splas' => $assign_splas, 'projects' => $projects
        ]);
    }

    public function server_summary_for_proyect(Request $request)
    {
        $servers = Server::selectRaw(
            'servers.idserver, servers.name AS server_name,servers.machine_name,servers.hostname,servers.service,
            servers.active,servers.is_deleted,projects.name AS project_name,projects.idproject as alp,
            sows.name AS sow_name,sows.version,sows.type,sows.idsow'
        )->join('projects', function ($join) {
            $join->on('projects.idproject', '=', 'servers.idproject');
        })->leftJoin('sows', function ($join) {
            $join->on('sows.idsow', '=', 'servers.idsow');
        })->where('projects.name', 'like', '%' . strtoupper($request->name) . '%')->orderBy('servers.is_deleted', 'asc')->get();

        return response()->json($servers);
    }

    public function server_summary_for_hostname_or_vmware(Request $request)
    {
        $servers = Server::selectRaw(
            'servers.idserver, servers.name AS server_name,servers.machine_name,servers.hostname,servers.service,
            servers.active,servers.is_deleted,projects.name AS project_name,projects.idproject as alp,
            sows.name AS sow_name,sows.version,sows.type,sows.idsow'
        )->join('projects', function ($join) {
            $join->on('projects.idproject', '=', 'servers.idproject');
        })->leftJoin('sows', function ($join) {
            $join->on('sows.idsow', '=', 'servers.idsow');
        })->where('servers.machine_name', 'like', '%' . strtoupper($request->name) . '%')
            ->orWhere('servers.hostname', 'like', '%' . strtoupper($request->name) . '%')
            ->orderBy('servers.is_deleted', 'asc')->get();

        return response()->json($servers);
    }

    public function update_server_summary($id, Request $request)
    {
        $server = Server::find($id);
        $active = str_replace($server->idproject, $request['server']['idproject'], $server->active);
        $server->idproject = $request['server']['idproject'];
        $server->active = $active;
        $server->idsow = $request['server']['idsow'] === null ? null : intval($request['server']['idsow']);
        $server->save();

        $assign_services = AssignService::where('idserver', $id)->first();
        if ($assign_services === null) {
            $assign_services = new AssignService();
            $assign_services->idserver = $id;
        }
        $assign_services->is_backup = $request['assign_service']['is_backup'];
        $assign_services->is_additional = $request['assign_service']['is_additional'];
        $assign_services->is_windows_license = $request['assign_service']['is_windows_license'];
        $assign_services->is_antivirus = $request['assign_service']['is_antivirus'];
        $assign_services->is_linux_license = $request['assign_service']['is_linux_license'];
        $assign_services->is_additional_spla = $request['assign_service']['is_additional_spla'];
        $assign_services->save();

        $assign_splas = SplaAssignedDiscount::join('spla_licenses', 'spla_licenses.idspla', '=', 'spla_assigned_discounts.idspla')->where('idserver', $id)->get();

        $keys = array_keys($request['assign_spla_licences']);
        for ($index = 0; $index < sizeof($keys); $index++) {
            if (sizeof($assign_splas) > 0) {

                if ($keys[$index] == 'SQL Server') $spla = collect($assign_splas)->filter(function ($value) {
                    return $value->type == 'SQL Server' || $value->type == 'SQL Server 2';
                })->first();
                else $spla = collect($assign_splas)->where('type', $keys[$index])->first();

                if ($spla === null) $this->save_assign_spla($request, $id, $keys[$index]);
                else {
                    if ($request['assign_spla_licences'][$keys[$index]]['idspla'] === null) $spla->delete();
                    else {
                        $spla->idspla = $request['assign_spla_licences'][$keys[$index]]['idspla'];
                        $spla->percentage = $request['assign_spla_licences'][$keys[$index]]['percentage'];
                        $spla->save();
                    }
                }
            } else $this->save_assign_spla($request, $id, $keys[$index]);
        }
        $assign_splas = SplaAssignedDiscount::select('iddiscount', 'percentage', 'idserver', 'spla_licenses.type', 'spla_licenses.idspla')
            ->join('spla_licenses', function ($join) {
                $join->on('spla_licenses.idspla', '=', 'spla_assigned_discounts.idspla');
            })->where('idserver', '=', $id)->get();
        return response()->json(['server' => $server, 'assign_services' => $assign_services, 'assign_splas' => $assign_splas]);
    }

    private function save_assign_spla($request, $id, $key)
    {
        if ($request['assign_spla_licences'][$key]['idspla'] === null) return;
        $assign_spla = new SplaAssignedDiscount();
        $assign_spla->idserver = $id;
        $assign_spla->idspla = $request['assign_spla_licences'][$key]['idspla'];
        $assign_spla->percentage = $request['assign_spla_licences'][$key]['percentage'];
        $assign_spla->save();
    }

    private function getServersAndSowsForCalculateCosts($date_start, $date_end, $idproject)
    {
        $servers = Server::selectRaw('
            servers.idserver, servers.name as server_name,servers.idproject,servers.is_deleted,sows.idsow,
            projects.name AS project_name, resources_history.name as resource_name,resources_history.amount,
            resources_history.date,assign_services.is_backup,assign_services.is_additional,
            assign_services.is_windows_license,assign_services.is_antivirus,assign_services.is_linux_license,
            assign_services.is_additional_spla
        ')->join('projects', function ($join) {
            $join->on('projects.idproject', '=', 'servers.idproject');
        })->join('resources_history', function ($join) use ($date_start, $date_end) {
            $join->on('resources_history.idserver', '=', 'servers.idserver');
            $join->whereBetween('resources_history.date', [$date_start, $date_end]);
        })->join('sows', function ($join) {
            $join->on('sows.idsow', '=', 'servers.idsow');
        })->leftJoin('assign_services', function ($join) {
            $join->on('assign_services.idserver', '=', 'servers.idserver');
        });

        if ($idproject !== null) $servers = $servers->where('servers.idproject', '=', $idproject);

        $servers = $servers->get();

        $sows = Sow::all();
        $spla_assigned_discounts = SplaAssignedDiscount::join('spla_licenses', 'spla_licenses.idspla', '=', 'spla_assigned_discounts.idspla')->get();
        $cost_maintenance = Project::selectRaw('
            projects.idproject, projects.name,
            sum(fourwalls.cost) as cost_fourwalls,
            sum(nexus.cost) as cost_nexus, sum(hps.cost) as cost_hps
        ')->leftJoin('fourwalls', function ($join) {
            $join->on('fourwalls.idproject', '=', 'projects.idproject');
            $join->where('fourwalls.is_deleted', false);
        })->leftJoin('nexus', function ($join) {
            $join->on('nexus.idproject', '=', 'projects.idproject');
            $join->where('nexus.is_deleted', false);
        })->leftJoin('hps', function ($join) {
            $join->on('hps.idproject', '=', 'projects.idproject');
            $join->where('hps.is_deleted', false);
        })->groupBy(['projects.idproject'])->get();

        return [$servers, $sows, $spla_assigned_discounts, $cost_maintenance];
    }

    private function get_servers_and_resources_filters($servers)
    {
        $filters = [];
        $resources = [];

        foreach ($servers as $server) {

            $object = [
                'idserver' => $server->idserver,
                'idproject' => $server->idproject,
                'server_name' => is_null($server->server_name) ? '' : $server->server_name,
                'project_name' => $server->project_name,
                'is_backup' => $server->is_backup,
                'is_additional' => $server->is_additional,
                'is_windows_license' => $server->is_windows_license,
                'is_antivirus' => $server->is_antivirus,
                'is_linux_license' => $server->is_linux_license,
                'is_additional_spla' => $server->is_additional_spla,
                'idsow' => $server->idsow,
                'is_deleted' => $server->is_deleted,
            ];

            if (!in_array($object, $filters)) {
                array_push($filters, [
                    'idserver' => $server->idserver,
                    'idproject' => $server->idproject,
                    'server_name' => is_null($server->server_name) ? '' : $server->server_name,
                    'project_name' => $server->project_name,
                    'is_backup' => $server->is_backup,
                    'is_additional' => $server->is_additional,
                    'is_windows_license' => $server->is_windows_license,
                    'is_antivirus' => $server->is_antivirus,
                    'is_linux_license' => $server->is_linux_license,
                    'is_additional_spla' => $server->is_additional_spla,
                    'idsow' => $server->idsow,
                    'is_deleted' => $server->is_deleted,
                ]);
            }

            if (!array_key_exists($server->idserver, $resources)) $resources[$server->idserver] = [];


            array_push($resources[$server->idserver], [
                'resource_name' => $server->resource_name,
                'amount' => $server->amount,
                'date' => $server->date,
            ]);
        }
        return [$filters, $resources];
    }

    private function getCostsByType($filters, $resources, $sows, $spla_assigned_discounts, $date_start, $date_end)
    {
        $costs = [];

        if ($date_start == null && $date_end == null) [$date_start, $date_end] = $this::getDatesCalculed();

        $days = Carbon::createFromFormat('d/m/Y', $date_start)->diffInDays(Carbon::createFromFormat('d/m/Y', $date_end));
        if ($days > 31 || $days < 28) $days = 30;

        foreach ($filters as $key => $filter) {
            $sow = collect($sows)->where('idsow', $filter['idsow'])->first();
            array_push($costs, [
                'idproject' => $filter['idproject'],
                'project_name' => $filter['project_name'],
                'server_name' => $filter['server_name'],
                'cost_cpu' => 0,
                'cost_ram' => 0,
                'cost_disk' => 0,
                'cost_splas' => 0,
                'license_so' => 0,
                'antivirus' => 0,
                'license_vspp' => 0,
                'license_srm' => 0,
                'cost_link' => 0,
                'license_cot' => 0,
                'backup' => 0,
                'mo_cloud_sw_cot' => 0,
                'cost_nexus' => 0,
                'cost_4_wall' => 0,
                'cost_hp_dccare' => 0,
                'sow_name' => 'N.A.',
            ]);

            if ($sow->is_deleted) continue;

            $costs[$key]['sow_name'] = $sow->version . ' ' . $sow->type . ' ' . $sow->name;
            /*  $days = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y')); */
            $days_off = collect($resources[$filter['idserver']])->where('resource_name', 'RAM')->where('amount', 0)->count();

            /* Obtain cost by CPU */
            $cost_cpu = 0;
            $cpus = collect($resources[$filter['idserver']])->where('resource_name', 'CPU')->sum('amount');
            $cost_cpu = $cpus * ($sow->cost_cpu / $days);
            $costs[$key]['cost_cpu'] = round($cost_cpu, 2);

            /* Obtain cost by RAM */
            $cost_ram = 0;
            $rams = collect($resources[$filter['idserver']])->where('resource_name', 'RAM')->sum('amount');
            $cost_ram = $rams * ($sow->cost_ram / $days);
            $costs[$key]['cost_ram'] = round($cost_ram, 2);

            /* Obtain cost by DISK */
            $cost_disk = 0;
            $disks_hdd = collect($resources[$filter['idserver']])->where('resource_name', 'HDD')->sum('amount');
            $disks_sdd = collect($resources[$filter['idserver']])->where('resource_name', 'SDD')->sum('amount');
            $cost_disk = ($disks_hdd * ($sow->cost_hdd_mechanical / $days)) + ($disks_sdd * ($sow->cost_hdd_solid / $days));
            $costs[$key]['cost_disk'] = round($cost_disk, 2);

            /* Obtain cost by SPLAs */
            $cost_splas = 0;
            $cost_splas = collect($spla_assigned_discounts)->where('idserver', $filter['idserver']);
            foreach ($cost_splas as $cost_spla) {
                $cost = 0;
                if (str_contains($cost_spla->type, 'SQL Server')) {
                    $lic_req = $this::licenceRequired($resources[$filter['idserver']]);
                    $cost = $lic_req * $cost_spla->cost;
                    $cost = $cost - ($cost * ($cost_spla->discount / 100));
                } else {
                    $cost = $cost_spla->cost - ($cost_spla->cost * ($cost_spla->discount / 100));
                }

                $costs[$key]['cost_splas'] += round($cost, 2);
            }


            $lic_so = 0;
            if (!is_null($filter['is_windows_license']) && $filter['is_windows_license']) $lic_so = (($sow->add_cost_win_license_cpu / $days) * $cpus) + (($sow->add_cost_win_license_ram / $days) * $rams);
            else if (!is_null($filter['is_linux_license']) && $filter['is_linux_license']) $lic_so = $sow->add_cost_linux_license - ($sow->add_cost_linux_license / $days * $days_off);
            $costs[$key]['license_so'] = round($lic_so, 2);

            $antivirus = 0;
            if (!is_null($filter['is_antivirus']) && $filter['is_antivirus']) $antivirus += $sow->add_cost_antivirus - ($sow->add_cost_antivirus / $days * $days_off);

            $lic_vssp = 0;
            $lic_vssp += ($sow->cost_license_vssp / $days) * $rams;
            $costs[$key]['license_vspp'] = round($lic_vssp, 2);

            $lic_srm = 0;
            $lic_srm += $sow->cost_license_vssp_srm;
            $costs[$key]['license_srm'] = round($lic_srm, 2);

            $lic_cot = 0;
            $lic_cot += $sow->cost_cot_monitoring;
            $costs[$key]['license_cot'] = round($lic_cot, 2);

            $cost_link = 0;
            $cost_link += (($sow->cost_link / $days) * $disks_hdd) + (($sow->cost_link / $days) * $disks_sdd);
            $costs[$key]['cost_link'] = round($cost_link, 2);

            /* Obtain cost backup */
            $cost_backup = (($sow->cost_backup_db / $days) * $disks_hdd) + (($sow->cost_backup_db / $days) * $disks_sdd);
            $costs[$key]['backup'] = round($cost_backup, 2);

            /* Obtain cost MO */
            $coost_mo = $sow->cost_mo_clo_sw_ge + $sow->cost_mo_cot;
            $costs[$key]['mo_cloud_sw_cot'] = round($coost_mo, 2);
        }

        return $costs;
    }

    private function getCostsByProject($filters, $resources, $sows, $spla_assigned_discounts, $cost_maintenance, $date_start, $date_end)
    {
        $costs = $this->getCostsByType($filters, $resources, $sows, $spla_assigned_discounts, $date_start, $date_end);
        $costs_projects = [];
        foreach ($costs as $cost) {
            $maintenance = collect($cost_maintenance)->where('idproject', $cost['idproject'])->first();
            if (array_key_exists($cost['idproject'], $costs_projects)) {
                $costs_projects[$cost['idproject']]['CPU'] += $cost['cost_cpu'];
                $costs_projects[$cost['idproject']]['RAM'] += $cost['cost_ram'];
                $costs_projects[$cost['idproject']]['DISK'] += $cost['cost_disk'];
                $costs_projects[$cost['idproject']]['cost_splas'] += $cost['cost_splas'];
                $costs_projects[$cost['idproject']]['lic_cloud'] += $cost['license_so'] + $cost['license_vspp'] + $cost['license_srm'] + $cost['license_cot'] + $cost['cost_link'] + $cost['antivirus'];
                $costs_projects[$cost['idproject']]['backup'] += $cost['backup'];
                $costs_projects[$cost['idproject']]['mo'] += $cost['mo_cloud_sw_cot'];
                $costs_projects[$cost['idproject']]['cost_total'] += $costs_projects[$cost['idproject']]['CPU'] + $costs_projects[$cost['idproject']]['RAM'] + $costs_projects[$cost['idproject']]['DISK'] + $costs_projects[$cost['idproject']]['cost_splas'] + $costs_projects[$cost['idproject']]['lic_cloud'] + $costs_projects[$cost['idproject']]['backup'] + $costs_projects[$cost['idproject']]['mo'] + $costs_projects[$cost['idproject']]['cost_maintenance'];
            } else {
                $costs_projects[$cost['idproject']] = [
                    'idproject' => $cost['idproject'],
                    'project_name' => $cost['project_name'],
                    'CPU' => $cost['cost_cpu'],
                    'RAM' => $cost['cost_ram'],
                    'DISK' => $cost['cost_disk'],
                    'cost_splas' => $cost['cost_splas'],
                    'lic_cloud' => $cost['license_so'] + $cost['license_vspp'] + $cost['license_srm'] + $cost['license_cot'] + $cost['cost_link'] + $cost['antivirus'],
                    'backup' => $cost['backup'],
                    'mo' => $cost['mo_cloud_sw_cot'],
                    'cost_maintenance' => $maintenance->cost_fourwalls ??= 0 + $maintenance->cost_nexus ??= 0 + $maintenance->cost_hps ??= 0,
                    'cost_total' => 0
                ];

                $costs_projects[$cost['idproject']]['cost_total'] = $costs_projects[$cost['idproject']]['CPU'] ??= 0 + $costs_projects[$cost['idproject']]['RAM'] ??= 0
                    + $costs_projects[$cost['idproject']]['DISK'] ??= 0 + $costs_projects[$cost['idproject']]['cost_splas'] ??= 0
                    + $costs_projects[$cost['idproject']]['lic_cloud'] ??= 0 + $costs_projects[$cost['idproject']]['backup'] ??= 0
                    + $costs_projects[$cost['idproject']]['mo'] ??= 0 + $costs_projects[$cost['idproject']]['cost_maintenance'] ??= 0;
            }
        }

        array_walk($costs_projects, function (&$item) {
            $item['CPU'] = number_format(round($item['CPU'], 2), 2);
            $item['RAM'] = number_format(round($item['RAM'], 2), 2);
            $item['DISK'] = number_format(round($item['DISK'], 2), 2);
            $item['cost_splas'] = number_format(round($item['cost_splas'], 2), 2);
            $item['lic_cloud'] = number_format(round($item['lic_cloud'], 2), 2);
            $item['backup'] = number_format(round($item['backup'], 2), 2);
            $item['mo'] = number_format(round($item['mo'], 2), 2);
            $item['cost_maintenance'] = number_format(round($item['cost_maintenance'], 2), 2);
            $item['cost_total'] = number_format(round($item['cost_total'], 2), 2);
        });

        return json_decode(json_encode(array_values($costs_projects)), false);
    }

    private function getCostsByServer($filters, $resources, $sows, $spla_assigned_discounts, $date_start, $date_end)
    {
        $costs = $this->getCostsByType($filters, $resources, $sows, $spla_assigned_discounts, $date_start, $date_end);

        $costs_servers = [];

        foreach ($costs as $key => $cost) {
            array_push($costs_servers, [
                'idproject' => $cost['idproject'],
                'project_name' => $cost['project_name'],
                'server_name' => $cost['server_name'],
                'sow_name' => $cost['sow_name'],
                'CPU' => 0,
                'RAM' => 0,
                'DISK' => 0,
                'cost_splas' => 0,
                'lic_cloud' => 0,
                'backup' => 0,
                'mo' => 0,
                'cost_total' => 0,
            ]);

            $costs_servers[$key]['CPU'] += $cost['cost_cpu'];
            $costs_servers[$key]['RAM'] += $cost['cost_ram'];
            $costs_servers[$key]['DISK'] += $cost['cost_disk'];
            $costs_servers[$key]['cost_splas'] += $cost['cost_splas'];
            $costs_servers[$key]['lic_cloud'] += $cost['license_so'] + $cost['license_vspp'] + $cost['license_srm'] + $cost['license_cot'] + $cost['cost_link'] + $cost['antivirus'];
            $costs_servers[$key]['backup'] += $cost['backup'];
            $costs_servers[$key]['mo'] += $cost['mo_cloud_sw_cot'];
            $costs_servers[$key]['cost_total'] += $cost['cost_cpu'] ??= 0 + $cost['cost_ram'] ??= 0 + $cost['cost_disk'] ??= 0
                + $cost['cost_splas'] ??= 0 + $cost['license_so'] ??= 0 + $cost['license_vspp'] ??= 0 + $cost['license_srm'] ??= 0
                + $cost['license_cot'] ??= 0 + $cost['cost_link'] ??= 0 + $cost['antivirus'] ??= 0 + $cost['backup'] ??= 0
                + $cost['mo_cloud_sw_cot'] ??= 0;
        }

        array_walk($costs_servers, function (&$item) {
            $item['CPU'] = number_format(round($item['CPU'], 2), 2);
            $item['RAM'] = number_format(round($item['RAM'], 2), 2);
            $item['DISK'] = number_format(round($item['DISK'], 2), 2);
            $item['cost_splas'] = number_format(round($item['cost_splas'], 2), 2);
            $item['lic_cloud'] = number_format(round($item['lic_cloud'], 2), 2);
            $item['backup'] = number_format(round($item['backup'], 2), 2);
            $item['mo'] = number_format(round($item['mo'], 2), 2);
            $item['cost_total'] = number_format(round($item['cost_total'], 2), 2);
        });

        return json_decode(json_encode($costs_servers), false);
    }

    public static function getDatesCalculed()
    {
        $date = Carbon::now();

        $date_start = date('15/m/Y', strtotime(date('Y-m-15') . '-1 month'));
        $date_end = date('15/m/Y');

        if ($date->format('d') >= 16) {
            $date_start = date('15/m/Y');
            $date_end = date('15/m/Y', strtotime(date('Y-m-15') . '+1 month'));
        }
        return [$date_start, $date_end];
    }

    private function getCacheSavedOrSaveCacheOrDeleteCache($keys, $values, $type)
    {
        if ($type === 'SAVE') {
            foreach ($keys as $i => $key) {
                Cache::put($key, $values[$i], 60);
            }
        } else if ($type === 'DELETE') {
            foreach ($keys as $key) {
                Cache::forget($key);
            }
        } else {
            $caches = [];
            foreach ($keys as $key) {
                array_push($caches, Cache::get($key));
            }
            return $caches;
        }
    }

    public static function licenceRequired($resources)
    {
        /* order by date and select ultimate cpu amount register up to 0 */
        $cpu = collect($resources)->where('resource_name', 'CPU')->where('amount', '>', 0)->sortByDesc('date')->first();

        $cpu = is_null($cpu) ? 0 : $cpu['amount'];
        $lic_req = 0;

        if ($cpu == 2) $lic_req = 1;
        else if ($cpu == 0) $lic_req = 0;
        else if ($cpu < 5) $lic_req = 2;
        else $lic_req = round($cpu / 2);

        return $lic_req;
    }
}
