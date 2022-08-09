<?php

namespace App\Http\Controllers;

use App\Models\AssignService;
use App\Models\Server;
use App\Models\Sow;
use App\Models\SplaAssignedDiscount;
use App\Models\SplaLicense;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function resource_consumption()
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
            $join->orderBy('resources_history.date', 'desc');
            $join->limit(4);
        })->groupBy(['servers.idserver', 'servers.name', 'servers.active', 'servers.idproject', 'project_name', 'service'])->get();

        return view('reports.IT-resources-consumption', ['servers' => $servers]);
    }

    public function resource_consumption_for_project_name(Request $request)
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
            $join->orderBy('resources_history.date', 'desc');
            $join->limit(4);
        })->where('projects.name', 'like', '%' . strtoupper($request->name) . '%')
            ->groupBy(['servers.idserver', 'servers.name', 'servers.active', 'servers.idproject', 'project_name', 'service'])->get();

        return response()->json($servers);
    }

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
            $join->orderBy('resources_history.date', 'desc');
            $join->limit(4);
        })->where('servers.hostname', 'like', '%' . strtoupper($request->name) . '%')
            ->orWhere('servers.machine_name', 'like', '%' . strtoupper($request->name) . '%')
            ->groupBy(['servers.idserver', 'servers.name', 'servers.active', 'servers.idproject', 'project_name', 'service'])->get();

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
            $join->orderBy('resources_history.date', 'desc');
            $join->limit(4);
        })->whereBetween('resources_history.date', [$request->date_start, $request->date_end])
            ->groupBy(['servers.idserver', 'servers.name', 'servers.active', 'servers.idproject', 'project_name', 'service'])->get();

        return response()->json($servers);
    }

    public function generate_excel()
    {
        return Excel::download([], 'servers.xlsx');
    }

    public function resource_consumption_grafic($id)
    {
        $server = Server::selectRaw('
            servers.name, resources_history.name as resource_name ,resources_history.amount,resources_history.date
        ')->join('resources_history', function ($join) {
            $join->on('resources_history.idserver', '=', 'servers.idserver');
            $join->orderBy('resources_history.date', 'desc');
        })->where('servers.idserver', $id)->get();
        return view('reports.grafics', ['server' => $server, 'name' => $server[0]->name]);
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

        $resources = $resources = Server::selectRaw('servers.idserver,jsonb_object_agg(resources_history.name,resources_history.amount) as resources')
            ->join('resources_history', function ($join) {
                $join->on('resources_history.idserver', '=', 'servers.idserver');
                $join->orderBy('resources_history.date', 'desc');
                $join->limit(4);
            })->groupBy('servers.idserver')->get();

        $assign_services = AssignService::select('assign_services.idserver', 'is_backup', 'is_additional', 'is_additional_spla', 'is_windows_license', 'is_antivirus', 'is_vcpu', 'is_linux_license')
            ->join('servers', function ($join) {
                $join->on('servers.idserver', '=', 'assign_services.idserver');
            })->get();

        $assign_splas = SplaAssignedDiscount::select('iddiscount', 'percentage', 'idserver', 'spla_licenses.type', 'spla_licenses.idspla')
            ->join('spla_licenses', function ($join) {
                $join->on('spla_licenses.idspla', '=', 'spla_assigned_discounts.idspla');
            })->get();

        return view('reports.server-summary', [
            'servers' => $servers, 'licenses' => $licenses,
            'sows' => $sows, 'resources' => $resources, 'assign_services' => $assign_services, 'assign_splas' => $assign_splas
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
        $server->idsow = $request['server']['idsow'] === null ? null : intval($request['server']['idsow']);
        $server->save();

        $assign_services = AssignService::where('idserver', '=', $id)->first();
        if ($assign_services === null) {
            $assign_services = new AssignService();
            $assign_services->idserver = $id;
        }
        $assign_services->is_backup = $request['assign_service']['is_backup'];
        $assign_services->is_additional = $request['assign_service']['is_additional'];
        $assign_services->is_windows_license = $request['assign_service']['is_windows_license'];
        $assign_services->is_antivirus = $request['assign_service']['is_antivirus'];
        $assign_services->is_vcpu = $request['assign_service']['is_vcpu'];
        $assign_services->is_linux_license = $request['assign_service']['is_linux_license'];
        $assign_services->is_additional_spla = $request['assign_service']['is_additional_spla'];
        $assign_services->save();

        $assign_splas = SplaAssignedDiscount::join('spla_licenses', 'spla_licenses.idspla', '=', 'spla_assigned_discounts.idspla')->where('idserver', '=', $id)->get();
        $keys = array_keys($request['assign_spla_licences']);
        for ($index = 0; $index < sizeof($keys); $index++) {
            if (sizeof($assign_splas) > 0) {
                $spla = collect($assign_splas)->where('type', $keys[$index])->first();
                if ($spla === null) {
                    $this->save_assign_spla($request, $id, $keys[$index]);
                } else {
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
}
